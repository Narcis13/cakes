---
feature_name: security-hardening
feature_title: Security Hardening
repository: Narcis13/cakes
epic_issue: 4
project_number: null
labels:
  - epic
  - feature/security-hardening
  - phase-1
  - phase-2
  - phase-3
  - phase-4
  - phase-5
  - phase-6
  - phase-7
published_at: 2026-01-11
---

# GitHub References

This feature has been published to GitHub.

## Links

- [Epic Issue](https://github.com/Narcis13/cakes/issues/4)
- Project Board: Not created (requires `gh auth refresh -s project,read:project` to add project scopes)

## Phase Issues

| #   | Title                                                    | Tasks | Status |
| --- | -------------------------------------------------------- | ----- | ------ |
| #5  | Phase 1: Login Security (Critical Priority)              | 9     | Open   |
| #6  | Phase 2: XSS Protection (Critical Priority)              | 9     | Open   |
| #7  | Phase 3: Form & Session Security (High Priority)         | 6     | Open   |
| #8  | Phase 4: File Upload & Transport Security (High Priority)| 4     | Open   |
| #9  | Phase 5: Configuration Hardening (Critical Priority)     | 8     | Open   |
| #10 | Phase 6: Access Control Fixes (Medium Priority)          | 3     | Open   |
| #11 | Phase 7: Audit Logging (Optional Enhancement)            | 5     | Open   |

## Labels

- `epic` - Feature epic marker
- `feature/security-hardening` - Feature-specific label
- `phase-1` through `phase-7` - Phase markers

## To Add Project Board

Run the following to enable project creation:
```bash
gh auth refresh -s project,read:project
gh project create --title "Feature: Security Hardening" --owner Narcis13
gh project link <project-number> --owner Narcis13 --repo Narcis13/cakes
```

Then add all issues to the project:
```bash
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/4"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/5"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/6"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/7"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/8"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/9"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/10"
gh project item-add <project-number> --owner Narcis13 --url "https://github.com/Narcis13/cakes/issues/11"
```
