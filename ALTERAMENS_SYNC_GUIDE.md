# Alteramens Sync Guide - Git Submodules Workflow

This guide documents how to use git submodules to manage the relationship between the `cakes` repository and the `alteramens` repository.

## 1. Overview

### What Are Git Submodules?

Git submodules allow you to include one Git repository inside another as a subdirectory while keeping their histories separate. The parent repository (`cakes`) stores a reference to a specific commit in the submodule repository (`alteramens`).

### Why Submodules for This Use Case?

| Approach | Pros | Cons |
|----------|------|------|
| **Submodules** (recommended) | Clean separation, independent histories, easy to push changes back | Requires learning submodule commands |
| Git Subtree | Simpler for read-only includes | Complex when pushing changes back |
| Manual Sync | No learning curve | Error-prone, no version tracking |

**Submodules are recommended** because:
- You can work on `alteramens` from within `cakes` and push changes back
- Each repo maintains its own commit history
- Clear tracking of which `alteramens` version `cakes` depends on
- Collaborators can easily get the exact version needed

---

## 2. Initial Setup

### Prerequisites

- Git version 2.13+ (for improved submodule support)
  ```bash
  git --version
  ```
- GitHub SSH or HTTPS access configured for `Narcis13` account
- Push access to both repositories

### Adding the Submodule

From the root of the `cakes` repository:

```bash
# Add alteramens as a submodule
git submodule add https://github.com/Narcis13/alteramens.git alteramens

# Or using SSH (recommended for push access):
git submodule add git@github.com:Narcis13/alteramens.git alteramens
```

### Configure to Track Main Branch

```bash
# Set the submodule to track the main branch
git config -f .gitmodules submodule.alteramens.branch main

# Commit the submodule addition
git add .gitmodules alteramens
git commit -m "Add alteramens as git submodule tracking main branch"
```

### Cloning the Parent Repo with Submodules

When cloning `cakes` fresh (for collaborators or on a new machine):

```bash
# Option 1: Clone with submodules in one command
git clone --recurse-submodules https://github.com/Narcis13/cakes.git

# Option 2: Clone first, then initialize submodules
git clone https://github.com/Narcis13/cakes.git
cd cakes
git submodule init
git submodule update
```

---

## 3. Daily Workflow

### Pulling Updates from Alteramens

When `alteramens` has been updated on GitHub and you want those changes in `cakes`:

```bash
# Method 1: Update submodule to latest on tracked branch (main)
git submodule update --remote alteramens

# Method 2: Go into the submodule and pull manually
cd alteramens
git checkout main
git pull origin main
cd ..

# After either method, commit the updated reference in cakes
git add alteramens
git commit -m "Update alteramens submodule to latest"
git push
```

### Making Changes to Alteramens from Within Cakes

```bash
# 1. Navigate to the submodule
cd alteramens

# 2. Ensure you're on a branch (not detached HEAD)
git checkout main

# 3. Make your changes
# ... edit files ...

# 4. Commit changes
git add .
git commit -m "Your commit message"

# 5. Push changes to alteramens repo
git push origin main

# 6. Go back to cakes and update the reference
cd ..
git add alteramens
git commit -m "Update alteramens submodule reference"
git push
```

### Pulling Updates in Both Repos at Once

```bash
# Pull cakes updates AND update submodule to its recorded commit
git pull
git submodule update

# Or pull cakes AND update submodule to latest remote
git pull
git submodule update --remote
```

---

## 4. Common Operations

### Checking Submodule Status

```bash
# Show current submodule commit
git submodule status

# Show if submodule has local changes
git status

# Detailed submodule info
git submodule summary
```

### Switching Branches in Submodule

```bash
cd alteramens
git checkout feature-branch
# Do work...
git checkout main
cd ..
```

### Updating Submodule URL

If the repository URL changes:

```bash
# Edit .gitmodules file or use:
git config -f .gitmodules submodule.alteramens.url NEW_URL

# Sync the configuration
git submodule sync

# Update the submodule
git submodule update --init --recursive
```

### Removing a Submodule

If you need to completely remove the submodule:

```bash
# 1. Deinitialize the submodule
git submodule deinit -f alteramens

# 2. Remove from git tracking
git rm -f alteramens

# 3. Remove from .git/modules
rm -rf .git/modules/alteramens

# 4. Commit the removal
git commit -m "Remove alteramens submodule"
```

---

## 5. Important Gotchas & Tips

### Detached HEAD State

When you run `git submodule update`, the submodule is checked out in **detached HEAD** state (pointing to a specific commit, not a branch).

**Before making changes**, always:
```bash
cd alteramens
git checkout main  # or your working branch
```

If you accidentally committed in detached HEAD:
```bash
cd alteramens
git checkout main
git merge HEAD@{1}  # merge your detached commits
```

### Understanding .gitmodules

The `.gitmodules` file in `cakes` root stores submodule configuration:

```ini
[submodule "alteramens"]
    path = alteramens
    url = https://github.com/Narcis13/alteramens.git
    branch = main
```

This file should be committed and shared with collaborators.

### Nested .git Directories

The `alteramens/` folder does NOT contain a `.git` folder directly. Instead:
- Submodule git data is stored in `.git/modules/alteramens/` in the parent repo
- A `.git` file (not folder) in `alteramens/` points to this location

### CI/CD Considerations

In GitHub Actions or other CI systems, ensure submodules are checked out:

```yaml
# GitHub Actions example
- uses: actions/checkout@v4
  with:
    submodules: true  # or 'recursive' for nested submodules
```

### Collaborator Onboarding

When a new collaborator clones the repo:

1. They must initialize submodules after cloning:
   ```bash
   git submodule init
   git submodule update
   ```

2. Or clone with `--recurse-submodules` flag

3. If they see an empty `alteramens/` folder, they forgot to initialize submodules

### Common Mistakes to Avoid

1. **Forgetting to push submodule changes first** - Always push `alteramens` changes before pushing `cakes` reference update
2. **Committing in detached HEAD** - Always checkout a branch before editing
3. **Not updating parent reference** - After updating submodule, commit the new reference in `cakes`
4. **Using `git pull` inside submodule without checkout** - May cause detached HEAD issues

---

## 6. Quick Reference Commands

### Setup
```bash
git submodule add git@github.com:Narcis13/alteramens.git alteramens
git submodule init
git submodule update
```

### Daily Use
```bash
# Pull submodule updates
git submodule update --remote alteramens

# Work in submodule
cd alteramens && git checkout main

# After submodule changes
cd alteramens
git add . && git commit -m "message" && git push
cd .. && git add alteramens && git commit -m "Update alteramens" && git push
```

### Diagnostics
```bash
git submodule status
git submodule summary
git diff --submodule
```

### Full Clone
```bash
git clone --recurse-submodules git@github.com:Narcis13/cakes.git
```

---

## Verification Checklist

After setup, verify with:

- [ ] `git submodule status` shows the alteramens commit hash
- [ ] `ls alteramens/` shows the repository contents
- [ ] `cat .gitmodules` shows correct URL and branch
- [ ] Changes made in `alteramens/` can be pushed to GitHub
- [ ] Changes pushed to `alteramens` on GitHub can be pulled into `cakes`
