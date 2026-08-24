# Git workflow

- **Never `git push` without the user's explicit go-ahead in that turn** —
  even after lint/build/tests all pass. Commit locally freely; push is the
  gated step, because it triggers an auto-deploy on both Railway (backend)
  and Vercel (frontend). This was learned the hard way: a push once went out
  before the user could react and had to be reverted.
- If a push happens before explicit confirmation and the user wants it
  undone, prefer `git revert` (new commit, preserves history) over
  `git reset --hard` + force-push.
- During an active redesign/refactor pass, the user may additionally ask to
  hold off on *committing* too (batch commits at the end instead of one per
  change) — that's a separate, narrower instruction than the push rule
  above; follow whichever the user most recently stated for the current
  task.
- **Update relevant documentation before committing, not after.** Standing
  instruction from the user (2026-08-25). For homepage/redesign work that
  means `.ai/redesign/plan.md` (add/update the round's row(s) — see its own
  header for format); for anything else (backend fixes, infra changes,
  tooling) that means `CHANGELOG.md`. Both should already reflect the
  change by the time it's committed, not patched in as an afterthought or a
  separate later commit.
