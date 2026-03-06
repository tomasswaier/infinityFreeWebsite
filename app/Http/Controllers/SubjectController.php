<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subjects;
use App\Models\SubjectTag;
use App\Models\SubjectRating;
use App\Models\Tag;

class SubjectController extends Controller
{
    public function showAllSubjects(Request $request, int $school_id)
    {
        $query = Subjects::query()
            ->orderBy("rating", "desc")
            ->where("school_id", "=", $school_id);
        $parameters = $request->except("_token");
        foreach ($parameters as $parameter => $id) {
            //if more than tag selectors will be added then this part will need adjusting based on $parameter
            $query->whereHas("tags", function ($q) use ($id) {
                $q->where("tag_id", "=", $id);
            });
        }

        $subjects = $query->get();
        foreach ($subjects as &$subject) {
            $subject["tags"] = Subjects::find($subject["id"])->tags()->get();
        }
        return view("subjects/index", [
            "subjects" => $subjects,
            "school_id" => $school_id,
            "tags" => Tag::query()->where("school_id", "=", $school_id)->get(),
        ]);
    }

    public function showSubject(Request $request, int $id = null)
    {
        if ($id == null) {
            return redirect("subjects");
        }
        $subject = Subjects::find($id);
        if ($subject == null) {
            return redirect("subjects");
        }
        $userRating = null;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
            if ($user_id) {
                $userRating = SubjectRating::where([
                        ["subject_id", "=", $id],
                        ["user_id", "=", $user_id],
                    ])->first();
                if (isset($userRating["rating"])) {
                    $userRating = $userRating["rating"];
                }
            }
        }

        return view("subjects/subject", [
            "subject" => $subject,
            "school_id" => $subject->school_id,
            "tags" => Subjects::find($subject["id"])->tags()->get(),
            "subject_user_rating" => $userRating,
        ]);
    }

    public function editSubject(Request $request, $schoolId, $subjectId = null)
    {
        $info = $request->all();
        $subject = null;
        $selectedTags = null;
        if (isset($subjectId)) {
            $subject = Subjects::find($subjectId);
            $temp = SubjectTag::select("tag_id")
                ->where("subject_id", "=", $subjectId)
                ->get();
            $selectedTags = [];
            foreach ($temp as $tag) {
                array_push($selectedTags, $tag["tag_id"]);
            }
        }
        $tags = Tag::query()->where("school_id", "=", $schoolId)->get();
        return view("admin/subjectCreator", [
            "allTags" => $tags,
            "selectedTags" => $selectedTags,
            "subject" => $subject,
            "school_id" => $schoolId,
        ]);
    }
    //
    public function saveSubject(Request $request)
    {
        $info = $request->all();
        if (isset($info["subjectId"])) {
            $subject = Subjects::find($info["subjectId"]);
        } else {
            $subject = new Subjects();
        }
        $subject->name = $info["subjectName"];
        $subject->school_id = $info["school_id"];
        $subject->description = $info["subjectDescription"];
        $subject->tldr = $info["subjectTldr"];
        $subject->rating = $info["subjectRating"];
        $subject->save();
        SubjectTag::query()->where("subject_id", "=", $subject->id)->delete();
        $info = $request->except(
            "_token",
            "subjectName",
            "subjectRating",
            "subjectDescription",
            "subjectTldr",
        );
        //Log::info($info);
        foreach ($info as $key => $val) {
            //Log::info(substr($key,0,7));
            if (strlen($key) > 7 && substr($key, 0, 7) == "tag_id_") {
                $id = intval(substr($key, 7, strlen($key) - 7));
                //Log::info($id);
                SubjectTag::create([
                    "subject_id" => $subject->id,
                    "tag_id" => $id,
                ]);
            }
        }
        return redirect("admin/subjectCreator/" . $info["school_id"]);
    }

    public function saveTag(Request $request)
    {
        //should I check the users rights here ? if im not mistaken then the csrf token should take care of someone just calling this but i'm not sure
        try {
            $info = $request->all();
            $tag = new Tag();
            $tag->name = $info["tagName"];
            $tag->school_id = $info["school_id"];
            $tag->save();
        } catch (\Throwable $th) {
            Log::error("Error while creating Tag:" . $th);
        }
        return redirect()->back();
    }
    public function deleteOldestSubjectRating(int $subjectRatingId): int
    {
        $countOfRows = count(
            SubjectRating::where("subject_id", "=", $subjectRatingId)->get(),
        );
        if ($countOfRows > 19) {
            SubjectRating::where("subject_id", "=", $subjectRatingId)
                ->oldest()
                ->first()
                ->delete();
        }
        return 1;
    }
    public function updateSubjectRating(int $subjectRatingId): int
    {
        $subject = Subjects::find($subjectRatingId);
        if (!$subject) {
            Log::error("error:unable to find Subject by specified id");
            return 0;
        }
        $meow = SubjectRating::where("subject_id", "=", $subjectRatingId)->avg(
            "rating",
        );
        $subject->rating = round($meow);
        $subject->save();
    }

    public function saveRating(Request $request, int $id)
    {
        try {
            $this->deleteOldestSubjectRating($id);
            $info = $request->all();
            if ($request["userRating"] > 10) {
                return redirect("subjects/info/" . $id);
            }
            $rating = new SubjectRating();
            SubjectRating::updateOrCreate(
                [
                    "user_id" => Auth::user()->id,
                    "subject_id" => $id,
                ],
                [
                    "rating" => $request["userRating"],
                ],
            );
            $this->updateSubjectRating($id);
        } catch (\Throwable $th) {
            Log::error("Error while saving SubjectRating:" . $th);
        }
        return redirect("subjects/info/" . $id);
    }
}
