BASE_PATH=$(pwd)

rm -rf "${BASE_PATH}/build"

mkdir -p "${BASE_PATH}/build"

rm -rf paidcommunities-wp

# clone repository to this directory.
echo 'cloning paidcommunities-wp repository...'
git clone git@github.com:paidcommunities/paidcommunities-wp.git

cd paidcommunities-wp

#git pull origin main

# Loop through api and components directories
for dir in api components license; do
    echo "Checking ${dir} directory..."
    cd "$dir" || exit

    if [ -d "node_modules" ]; then
        echo "node_modules exists in ${dir}. Running npm update..."
        npm update
    else
        echo "node_modules does not exist in ${dir}. Running npm install..."
        npm install
    fi

    npm run build:prod

    cd .. || exit
done

echo 'copying dist directory to build'
cp -R api/build/* components/build/* components/build-style/*  license/build/* "${BASE_PATH}/build"

cd "$BASE_PATH" || exit