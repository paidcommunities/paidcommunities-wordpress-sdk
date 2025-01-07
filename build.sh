NAME="paidcommunities-php-sdk"
BASE_PATH=$(pwd)
BUILD_PATH="${BASE_PATH}/build"
DEST_PATH="$BUILD_PATH/$NAME"

echo 'Creating build directory...'
#create the build directory
rm -rf "$BUILD_PATH"

mkdir -p "$DEST_PATH"

#rsync files into destination path
rsync -rc --exclude-from="${BASE_PATH}/.distignore" "$BASE_PATH/" "$DEST_PATH/" --delete

# run composer install
composer install --no-dev --optimize-autoloader -d "$DEST_PATH" || exit "$?"

# cd into build path
cd "$BUILD_PATH"

zip -q -r "$NAME.zip" "$NAME/"





