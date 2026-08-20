# Multi-Environment CI/CD Pipeline for PHP on AWS

A production-style CI/CD pipeline that automatically builds and deploys a PHP application across **Dev, Staging, and Production** environments on AWS — fully provisioned using **Infrastructure as Code (CloudFormation)**.

## Architecture

```
GitHub (dev branch)     → CodePipeline (Dev)     → CodeBuild → EC2 (Dev)
GitHub (staging branch) → CodePipeline (Staging)  → CodeBuild → EC2 (Staging)
GitHub (main branch)    → CodePipeline (Prod)     → CodeBuild → Manual Approval → EC2 (Prod)
```

Each environment runs on its own isolated EC2 instance, secured by dedicated Security Groups, with deployment handled via SSH/rsync and secrets managed through AWS Secrets Manager.

## Key Features

- **Branch-based deployment** — pushes to `dev`, `staging`, and `main` trigger independent pipelines for each environment, mirroring real-world release workflows
- **Manual approval gate before Production** — no code reaches the live environment without explicit human sign-off
- **Infrastructure as Code** — every resource (VPC, EC2, Security Groups, IAM roles, CodeBuild, CodePipeline) is defined and version-controlled in CloudFormation
- **Least-privilege IAM** — separate roles for CodeBuild and CodePipeline, each scoped to only the permissions they need
- **Secure secrets handling** — SSH private key stored in AWS Secrets Manager and retrieved at build time, never hardcoded
- **Automated dependency management** — Composer-managed PHP dependencies, built fresh in each pipeline run

## Tech Stack

| Layer | Technology |
|---|---|
| Application | PHP 8.x, Composer, Carbon |
| CI/CD | AWS CodePipeline, AWS CodeBuild |
| Compute | Amazon EC2 (Ubuntu 22.04, Apache) |
| Secrets | AWS Secrets Manager |
| IAM | Least-privilege service roles |
| IaC | AWS CloudFormation (YAML) |
| Source Control | GitHub (branch-per-environment strategy) |

## Pipeline Flow

1. Developer pushes code to `dev`, `staging`, or `main`
2. CodePipeline detects the change via a GitHub App connection and triggers automatically
3. CodeBuild installs dependencies (`composer install`), retrieves the SSH key from Secrets Manager, and deploys the application to the corresponding EC2 instance via `rsync`
4. For `main` (Production), the pipeline pauses at a **Manual Approval** stage — a reviewer must approve before deployment proceeds
5. Application is live on the target environment, reachable over HTTP

## Repository Structure

```
├── public/
│   └── index.php              # Application entry point
├── src/
│   └── Controllers/
│       └── HomeController.php # Application logic
├── buildspec-dev.yml          # Build & deploy instructions for Dev
├── buildspec-staging.yml      # Build & deploy instructions for Staging
├── buildspec-prod.yml         # Build & deploy instructions for Prod
├── composer.json
└── README.md
```

## What This Project Demonstrates

- Designing and deploying multi-environment infrastructure entirely through CloudFormation
- Implementing secure, automated CI/CD pipelines with environment-specific triggers
- Managing secrets and credentials without exposing them in source control
- Applying least-privilege security principles across IAM roles and network access
- Debugging real infrastructure issues end-to-end, including dependency version conflicts, SSH key formatting, file permission errors, and GitHub webhook configuration

## Future Improvements

- Migrate compute layer to ECS Fargate for containerized deployments
- Add CloudWatch alarms and automated rollback on deployment failure
- Introduce a CloudFront distribution in front of Production for HTTPS and caching
- Add automated testing stage prior to deployment
