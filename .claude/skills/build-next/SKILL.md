---
name: build-next
description: Build the next unfinished slice of the Global Consultancy Laravel project (or a given slice number). Use when the user says /build-next, "continue the build", "next slice", or "build slice N".
---

# Build the next slice

Argument: optional slice number (e.g. `04`). Without it, pick the first row in docs/PROGRESS.md that is not `done`.

## Read only this
1. `docs/PROGRESS.md` (status + log).
2. The one slice file for this slice in `docs/slices/`.
3. Only when that slice needs it: `docs/DESIGN-LANDING.md` (slice 12) or `docs/HOSTING-HOSTINGER.md` (slices 05, 14, 15).
4. Code you need, found via `graphify query "..."` first, then targeted reads.

Do NOT read `PRODUCTION-PROJECT-PLAN-GLOBAL-CONSULTANCY.md` whole. If a detail is missing, grep it for one keyword and read only matching lines.
Do NOT read vendor/, node_modules/, public/build/, storage/, or lock files.

## Steps
1. Mark the slice `in-progress` in PROGRESS.md.
2. For each feature in the slice: write the failing feature/policy test, then migration → model + factory → policy → form request → service → controller → Blade → route, then make it pass.
3. Follow the rules in CLAUDE.md (scoped queries, policies, pagination 20, eager loading, private docs, no fake data).
4. Run only the tests for this slice while iterating: `php artisan test --filter=<Area>`. Run the full suite once at the end, plus `vendor/bin/pint` and `npm run build` if frontend changed.
5. Run `graphify update .` after code changes.
6. Check the slice's "Done when". If it fails, fix or mark `blocked` with reason.
7. Mark `done`, append one log line, commit: `git add -A && git commit -m "slice NN: <title>"`.
8. Report in under 10 lines: what was built, test result, anything blocked. Then stop. Do not start the next slice unless asked.

## Token rules
- Pipe long command output through `tail -40`. Use `--quiet`/`-q` flags.
- Don't re-read files you just wrote. Don't print whole files to "check".
- Use subagents only if the user asks.
