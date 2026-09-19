# Vantage Point International Website — Containerized CI/CD Project

A PHP + MySQL website, containerised with Docker and deployed through a GitHub Actions
CI/CD pipeline that builds, tests, pushes to Docker Hub, and deploys locally.

## Project structure

```
.
├── app/                        # website source (PHP, HTML, CSS, JS, images)
├── db/init.sql                 # database schema, auto-run on first MySQL start
├── Dockerfile                  # builds the PHP+Apache image
├── docker-compose.yml          # runs the website + MySQL together
├── .github/workflows/ci-cd.yml # CI/CD pipeline
└── README.md
```

## 1. Run it locally first (sanity check)

```bash
docker compose up --build
```

Then open http://localhost:8080 in your browser. You should see the homepage.

Test the dynamic parts:
- Login page: http://localhost:8080/Login_Form.php
  - Demo user: `demo` / `demo123`
  - Admin: `admin` / `AdMiN#010`
- Submit a ticket: http://localhost:8080/Ticket%20Submission.php
- View tickets: http://localhost:8080/view_tickets.php
- Contact form: http://localhost:8080/contact.php

Stop it with `docker compose down` (add `-v` to also wipe the database volume).

## 2. Push this project to GitHub

```bash
git init
git add .
git commit -m "Initial commit: containerised website"
git branch -M main
git remote add origin https://github.com/<your-username>/<your-repo>.git
git push -u origin main
```

Keep committing in small, meaningful steps as you go (e.g. "Add Dockerfile",
"Add docker-compose", "Add CI/CD workflow") — this is graded separately.

## 3. Create the Docker Hub repository

1. Sign in at https://hub.docker.com
2. Create a repository named `vantage-point-website`
3. Go to **Account Settings → Security → Access Tokens** and generate a new
   token (this is safer than using your real password in CI).

## 4. Add GitHub Actions secrets

In your GitHub repo: **Settings → Secrets and variables → Actions → New repository secret**

| Secret name          | Value                                |
|-----------------------|---------------------------------------|
| `DOCKERHUB_USERNAME` | your Docker Hub username             |
| `DOCKERHUB_TOKEN`    | the access token you just generated  |

## 5. Set up a self-hosted runner (for the "deploy to local computer" step)

GitHub Actions normally runs on GitHub's own cloud servers, so it can't touch
your PC directly. To satisfy the "deploy to local computer" requirement, you
register your own machine as a **self-hosted runner**:

1. In your GitHub repo: **Settings → Actions → Runners → New self-hosted runner**
2. Pick your OS and follow the exact commands shown (they look like this on Linux/macOS):
   ```bash
   mkdir actions-runner && cd actions-runner
   curl -o actions-runner.tar.gz -L <url-from-github>
   tar xzf actions-runner.tar.gz
   ./config.sh --url https://github.com/<your-username>/<your-repo> --token <token-from-github>
   ./run.sh
   ```
3. Leave that terminal window running (or install it as a background service —
   GitHub shows a `svc.sh install` option) so it's alive to receive jobs during
   the CI/CD run and during your viva.
4. Make sure Docker is installed and running on this same machine, since the
   `deploy-local` job in the workflow calls `docker compose` directly on it.

## 6. Trigger the pipeline

Push a commit to `main` (or use **Actions → CI/CD Pipeline → Run workflow**).
Watch it run under the **Actions** tab in GitHub:

1. **build-and-test** — lints every PHP file
2. **build-and-push** — builds the Docker image and pushes it to Docker Hub
3. **deploy-local** — runs on your own PC via the self-hosted runner, pulls the
   new image and restarts the containers with `docker compose`

## 7. Viva demo checklist

- [ ] Show the GitHub repo and commit history
- [ ] Show the Dockerfile and docker-compose.yml and explain each line
- [ ] Show the Docker Hub repository with the pushed image
- [ ] Make a small visible change (e.g. edit some text in `app/index.html`), commit, and push
- [ ] Switch to the **Actions** tab and watch the workflow run live, stage by stage
- [ ] Once it finishes, refresh http://localhost:8080 on your machine and show
      the change is live — proving the full pipeline works end to end
- [ ] Demonstrate the login, ticket submission and contact form actually
      writing to the MySQL container (`docker exec -it vantage-point-db mysql -uroot -prootpassword vantage_point_international -e "SELECT * FROM software_issue_tickets;"`)

## Notes on the source code

- `Login from with user and admin.php` is leftover code from an earlier
  version pointing at a different, unused database (`acbt`/`student`) and
  isn't linked from anywhere in the site — safe to ignore or delete.
- The real login is `Login_Form.php`, wired to the `user_information` table
  (user login) and a hardcoded admin check (`admin` / `AdMiN#010`).
- Database credentials are read from environment variables
  (`DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`), set in `docker-compose.yml`,
  so the same code works unchanged both locally and in CI.
