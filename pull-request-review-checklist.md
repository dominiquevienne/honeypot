This is the checklist that we try to go through for every single pull request that we get. If you're wondering why it takes so long for us to accept pull requests, this is why.

## General

- [ ] Is this change useful to the project, or something that we think will benefit others greatly?
- [ ] Check for overlap with other PRs.
- [ ] Think carefully about the long-term implications of the change. How will it affect existing projects that are dependent on this? How will it affect our projects? If this is complicated, do we really want to maintain it forever? Is there any way it could be implemented as a separate package, for better modularity and flexibility?

## Check the Code

- [ ] If it does too much, ask for it to be broken up into smaller PRs.
- [ ] Is it consistent?
- [ ] Review the changes carefully, line by line. Make sure you understand every single part of every line. Learn whatever you do not know yet.
- [ ] Take the time to get things right. PRs almost always require additional improvements to meet the bar for quality. Be very strict about quality. This usually takes several commits on top of the original PR.

## Check the Tests

- [ ] Does it have tests? If not:

  - [ ] Comment on the PR "Can you please add tests for this code", or...
  - [ ] Write the tests yourself.

- [ ] Do the tests pass for all of the following? If not, write a note in the PR, or fix them yourself.

  - [ ] Windows - Apache - PHP 5.5
  - [ ] Windows - Apache - PHP 5.6
  - [ ] Windows - Apache - PHP 7.0
  - [ ] Windows - Apache - PHP 7.1
  - [ ] Windows - Nginx - PHP 5.5
  - [ ] Windows - Nginx - PHP 5.6
  - [ ] Windows - Nginx - PHP 7.0
  - [ ] Windows - Nginx - PHP 7.1
  - [ ] OSX / Debian - Apache - PHP 5.5
  - [ ] OSX / Debian - Apache - PHP 5.6
  - [ ] OSX / Debian - Apache - PHP 7.0
  - [ ] OSX / Debian - Apache - PHP 7.1
  - [ ] OSX / Debian - Nginx - PHP 5.5
  - [ ] OSX / Debian - Nginx - PHP 5.6
  - [ ] OSX / Debian - Nginx - PHP 7.0
  - [ ] OSX / Debian - Nginx - PHP 7.1
  - [ ] Linux - Apache - PHP 5.5
  - [ ] Linux - Apache - PHP 5.6
  - [ ] Linux - Apache - PHP 7.0
  - [ ] Linux - Apache - PHP 7.1
  - [ ] Linux - Nginx - PHP 5.5
  - [ ] Linux - Nginx - PHP 5.6
  - [ ] Linux - Nginx - PHP 7.0
  - [ ] Linux - Nginx - PHP 7.1

## Check the Docs

- [ ] Does it have docs? If not:

  - [ ] Comment on the PR "Can you please add docs for this feature", or...
  - [ ] Write the docs yourself.

- [ ] If any new functions/classes are added, do they contain docstrings?

## Credit the Authors

- [ ] Add name and URL.
- [ ] Copy and paste title and PR number.
- [ ] Thank them for their hard work.

## Close Issues

- [ ] Merge the PR branch. This will close the PR's issue.
- [ ] Close any duplicate or related issues that can now be closed. Write thoughtful comments explaining how the issues were resolved.

## Release

- [ ] Decide whether the changes in master make sense as a major, minor, or patch release.
- [ ] Look at the clock. If you're tired, release later when you have time to deal with release problems.