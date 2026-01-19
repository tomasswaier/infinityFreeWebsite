{
  description = "laravel with postgres flake";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs?ref=nixos-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      system = "x86_64-linux";
      pkgs = nixpkgs.legacyPackages.${system};
    in {
      devShells.${system}.default = pkgs.mkShell {
        packages = [
          pkgs.nodejs
          pkgs.php84
          pkgs.laravel
          pkgs.php84Packages.composer
          pkgs.filezilla
          # you need mysql so ig install mysql84 or xampp
        ];
      };

  };

}
