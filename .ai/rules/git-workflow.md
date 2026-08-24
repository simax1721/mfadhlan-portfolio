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
