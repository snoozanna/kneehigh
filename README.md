<img src="http://getkirby.com/assets/images/github/starterkit.jpg" width="300">

**Kirby: the CMS that adapts to any project, loved by developers and editors alike.**
The Starterkit is a full-blown Kirby installation with a lot of example content, blueprints, templates and more.
It is ideal for new users to explore many of Kirby's options and get to know the Panel.

You can learn more about Kirby at [getkirby.com](https://getkirby.com).

<img src="http://getkirby.com/assets/images/github/starterkit-screen.png" />

### Try Kirby for free

You can try Kirby and the Starterkit on your local machine or on a test server as long as you need to make sure it is the right tool for your next project. … and when you’re convinced, [buy your license](https://getkirby.com/buy).

The Starterkit is a demo of basic Kirby features. It's not recommended to be used "as is" in production. Please, follow our documentation closely for more features and guides on how to build secure, high-quality websites with Kirby.

While Kirby as the CMS software itself requires you to purchase a license, we consider the files primarily connected to this Starterkit (assets, templates, snippets...) free to use under the MIT license. Feel free to start building your own project with them.

### Get going

Read our guide on [how to get started with Kirby](https://getkirby.com/docs/guide/quickstart).

You can download the latest version of the Starterkit from https://download.getkirby.com/.
If you are familiar with Git, you can clone Kirby's Starterkit repository from Github.

    git clone https://github.com/getkirby/starterkit.git

## What's Kirby?

- **[getkirby.com](https://getkirby.com)** – Get to know the CMS.
- **[Try it](https://getkirby.com/try)** – Take a test ride with our online demo. Or download one of our kits to get started.
- **[Documentation](https://getkirby.com/docs/guide)** – Read the official guide, reference and cookbook recipes.
- **[Issues](https://github.com/getkirby/kirby/issues)** – Report bugs and other problems.
- **[Feedback](https://feedback.getkirby.com)** – You have an idea for Kirby? Share it.
- **[Forum](https://forum.getkirby.com)** – Whenever you get stuck, don't hesitate to reach out for questions and support.
- **[Discord](https://chat.getkirby.com)** – Hang out and meet the community.
- **[YouTube](https://youtube.com/kirbyCasts)** - Watch the latest video tutorials visually with Bastian.
- **[Mastodon](https://mastodon.social/@getkirby)** – Spread the word.
- **[Bluesky](https://bsky.app/profile/getkirby.com)** – Tell a friend.

---

© 2009 Bastian Allgeier
[getkirby.com](https://getkirby.com) · [License agreement](https://getkirby.com/license)

### power up

kirby
composer start

vite
npm run dev

## Synchronize your contents

As mentioned above, deployment of your code base (templates and configuration) and dependencies (Kirby and Composer) is done via Git deployment. Deploying the content is a separate step. We recommend to use rsync to upload or download new contents to and from your remote fortrabbit App (see also our rsync article). On your local computer in the Terminal in the kirby project folder execute:

# SYNC UP: from local to remote

rsync -av ./content {{app-name}}@deploy.{{region}}.frbit.com:~
It works also the other way around. For example in a case, where you have done some edits online and want those changes to be reflected in your local development environment:

# SYNC DOWN: from remote to local

rsync -av {{app-name}}@deploy.{{region}}.frbit.com:~/content ./

# Content & Deployment Workflow — Kneehigh Kirby Site (fortrabbit)

This README explains how to update **code** and **content** for this site, and how
to avoid the sync/permissions issues we hit while first setting this up.

---

## The two things that move separately

|                                                               | Where it lives            | How it gets to the live site                           |
| ------------------------------------------------------------- | ------------------------- | ------------------------------------------------------ |
| **Code** (templates, blueprints, config, assets, build files) | Git repo, `deploy` branch | `git push` → fortrabbit auto-builds & deploys          |
| **Content** (`content/` folder — text files, images, PDFs)    | NOT in Git (`.gitignore`) | `rsync` over SSH, or edited directly in the live Panel |

**Never put `content/` in Git.** It's already excluded on purpose — this is correct
and matches fortrabbit's own recommended setup for Kirby.

---

## Connection details

```
Production environment: {{your-app}}@ssh.{{your-region}}.frbit.app
```

> **This file uses placeholders (`{{your-app}}`, `{{your-region}}`) on purpose —
> do not commit real SSH hostnames/usernames to a public repo.** Get the real
> values from the fortrabbit dashboard (**App → Code Access**) and substitute
> them in when running any command below. If you want a personal copy with the
> real values filled in for quick copy-pasting, keep it outside the repo (e.g.
> a local-only notes file or your password manager), not in Git.

---

## Recommended day-to-day workflow

### For small, ad-hoc content edits (text changes, adding a single page, swapping one image)

**Do these directly in the live Panel** (`https://{{your-app}}.{{your-region}}.frbit.app/panel`).

Why: files created through the Panel are written by the web server itself, with
correct ownership and permissions from the start. This sidesteps the whole class
of permissions/sync bugs we hit doing this from a local (iCloud-synced) Mac.

**Afterwards, pull those changes down to local** so your local copy doesn't go
stale and you have a backup:

```bash
rsync -av {{your-app}}@ssh.{{your-region}}.frbit.app:content/ ./content/
```

### For bulk content work (imports, restructuring, adding many files at once)

Doing this by hand in the Panel isn't practical, so do it locally, then push up
— but follow this exact process, since bulk syncs are where things went wrong:

```bash
# 1. ALWAYS pull down first, so you don't overwrite anything added directly
#    on the live site (e.g. test entries, quick edits) since your last local sync
rsync -av {{your-app}}@ssh.{{your-region}}.frbit.app:content/ ./content/

# 2. Make your local changes / run your import script

# 3. Push up — these flags matter:
rsync -av --ignore-times --no-perms \
  --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r \
  ./content/ {{your-app}}@ssh.{{your-region}}.frbit.app:content/
```

**Why each flag:**

- `--ignore-times` — forces rsync to actually compare/send files instead of
  trusting timestamps, which can be unreliable coming from an iCloud Drive folder
  and caused files to silently not transfer earlier.
- `--no-perms --chmod=...` — stops rsync copying your local folder permissions
  (iCloud Drive folders often default to `700`, owner-only), which previously
  blocked the web server from reading `content/` at all. This forces sane
  `755`/directories and `644`/files instead.

### After any bulk content sync, always verify

```bash
# Compare file counts, local vs remote
find content -type f | wc -l
ssh {{your-app}}@ssh.{{your-region}}.frbit.app "find content -type f | wc -l"
```

These numbers should match. If they don't, re-run the sync command — don't assume
a "0 files changed" summary means everything is already correct.

### Clear caches after a bulk content change

```bash
# Kirby's UUID/page cache
ssh {{your-app}}@ssh.{{your-region}}.frbit.app "rm -rf site/cache/*"

# Generated image thumbnails
ssh {{your-app}}@ssh.{{your-region}}.frbit.app "rm -rf media/pages/* media/site/* media/users/*"
```

(Note: fortrabbit's own deploy process already runs `rm -rf site/cache/*/pages`
automatically after every **code** deploy — but this doesn't happen on content-only
syncs, so do it manually after bulk content changes.)

---

## Deploying code changes

```bash
git checkout deploy
git pull          # if there are remote changes, resolve any divergence first
git push          # triggers an automatic build & deploy on fortrabbit
```

Check build progress and logs in the fortrabbit dashboard under your app's
**Deployments** tab.

---

## Uploading large numbers of images

If you're adding many/large photos:

1. **Resize oversized source images before upload** where practical — very large
   originals (multi-MB DSLR files, 4000px+) can exceed PHP's memory limit when
   Kirby generates thumbnails on first view. Aim for a sensible max dimension
   (e.g. ~2500px on the long edge) unless you specifically need full-res originals
   available.
2. **Keep PHP's memory limit raised** (currently 512M, set in the fortrabbit
   dashboard under App → PHP settings) while adding new batches of images — the
   memory spike happens the _first time_ each image is viewed and its thumbnail
   is generated, not on upload. It's safe to leave this raised permanently.
3. Optionally, "warm" new images after upload by visiting each new page once
   (or scripting a loop of requests) so thumbnails are pre-generated before real
   visitors hit them.

---

## Quick troubleshooting checklist

If content added/synced isn't appearing on the live site or in the Panel:

1. **Check file counts match** (see verification step above).
2. **Check permissions** — directories should be `755`, files `644`:
    ```bash
    ssh {{your-app}}@ssh.{{your-region}}.frbit.app "find content -type d -exec chmod 755 {} \;"
    ssh {{your-app}}@ssh.{{your-region}}.frbit.app "find content -type f -exec chmod 644 {} \;"
    ```
3. **Clear caches** (see above).
4. **Check the fortrabbit dashboard → Logs tab** for PHP errors (SSH log tailing
   also works: `ssh {{your-app}}@log.{{your-region}}.frbit.app tail source:web_php_error`
   while reloading the page).
5. **Test locally** (`php -S localhost:8000`, visit `/panel`) with the exact same
   `content/` folder — if it works locally but not live, it's environment-specific
   (permissions, memory, config); if it fails locally too, it's a content/structure
   problem, not a hosting one.
