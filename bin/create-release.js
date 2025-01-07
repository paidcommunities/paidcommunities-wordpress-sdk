const path = require('path');
const fs = require('fs');
require('dotenv').config({
    path: path.resolve(path.dirname(__dirname), '.env')
});
const {Octokit} = require('octokit');

const octokit = new Octokit({
    auth: process.env.GITHUB_TOKEN
});

const owner = process.env.GITHUB_OWNER;
const repo = process.env.GITHUB_REPOSITORY;
const tag = process.env.GITHUB_TAG;

// check if release exists

(async () => {
    let release;
    try {
        release = await octokit.request('GET /repos/{owner}/{repo}/releases/tags/{tag}', {
            owner: owner,
            repo: repo,
            tag: tag
        })
    } catch (error) {
        console.log('Release not found');
    }
    try {
        if (!release) {
            release = await octokit.request(`POST /repos/{owner}/{repo}/releases`, {
                owner: owner,
                repo: repo,
                tag_name: tag,
                target_commitish: 'main',
                name: 'v1.0.0',
                body: 'Release version 1.0.0',
                draft: false,
            })
        } else {
            console.log('Release: ', release.data);
        }
    } catch (error) {
        console.log(error);
    }
    try {
        console.log('Release ID: ', release.data.id);
        const filePath = path.resolve(path.dirname(__dirname), 'build/paidcommunities-php-sdk.zip');
        const data = fs.readFileSync(filePath);
        const result = await octokit.rest.repos.uploadReleaseAsset({
            owner: owner,
            repo: repo,
            release_id: release.data.id,
            data: data,
            name: 'v1.0.0.zip',
        });
        /*const result = await octokit.request(`POST /repos/{owner}/{repo}/releases/{release_id}/assets{?name}`, {
            owner: owner,
            repo: repo,
            release_id: release.data.id,
            name: 'paidcommunities-php-sdk.zip',
            /!*label: '',
            data: '',
            headers: {
                'Content-Type': 'application/zip'
            }*!/
        });*/
    } catch (error) {
        console.log(error);
    }
})()