#!/usr/bin/env bash
#
# Build a package and pack it as an installable ZIP in build/.
#
# Usage: bin/package.sh themes/skt-starter
#        bin/package.sh plugins/skt-blocks
#
# Files listed in the package's .distignore are left out.

set -euo pipefail

root="$(cd "$(dirname "$0")/.." && pwd)"
package="$(cd "${1:?Usage: bin/package.sh <package-directory>}" && pwd)"
slug="$(basename "$package")"

if [[ -f "$package/style.css" ]]; then
	header="$package/style.css"
else
	header="$package/$slug.php"
fi

version="$(sed -n 's/^[[:space:]*]*Version:[[:space:]]*//p' "$header" | head -n 1 | tr -d '[:space:]')"

if [[ -z "$version" ]]; then
	echo "Could not read the version from $header" >&2
	exit 1
fi

for command in npm rsync zip; do
	command -v "$command" >/dev/null || { echo "$command is required" >&2; exit 1; }
done

(cd "$package" && npm ci && npm run build)

stage="$(mktemp -d)"
trap 'rm -rf "$stage"' EXIT

rsync -a --exclude-from="$package/.distignore" "$package/" "$stage/$slug/"

mkdir -p "$root/build"
archive="$root/build/$slug-$version.zip"
rm -f "$archive"
(cd "$stage" && zip -qr "$archive" "$slug")

echo "Created ${archive#"$root/"}"
