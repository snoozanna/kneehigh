#!/usr/bin/env bash
# Backup then replace 'Category:' with 'Format:' in content files
set -euo pipefail
DIR="$(cd "$(dirname "$0")/.." && pwd)"
TARGETS=("$DIR/content/3_archive" "$DIR/content/5_people")
for T in "${TARGETS[@]}"; do
  if [ -d "$T" ]; then
    find "$T" -type f -name "*.txt" -print0 | while IFS= read -r -d '' f; do
      cp -p "$f" "$f.bak"
      sed -E 's/^Category:/Format:/I' "$f.bak" > "$f"
    done
  fi
done

echo "Converted Category: -> Format: and created .bak backups."