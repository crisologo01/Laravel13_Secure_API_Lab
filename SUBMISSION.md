# Submission — Laravel 13 Secure API Lab

**Course:** Integrative Programming & Technologies II  
**Module:** Module 5 — Lesson 1 (Secure API Design and Token-Based Authentication)  
**Name:** _[Your Name]_  
**Section:** _[Your Section]_  
**Repository:** https://github.com/crisologo01/Laravel13_Secure_API_Lab.git  

## Screenshots checklist

The following Postman evidence screenshots are in the `evidence/` folder:

- [ ] 01-public-api-200.png — GET /api/hello → 200 OK
- [ ] 02-register-201.png — POST /api/register → 201 Created
- [ ] 03-login-200.png — POST /api/login → 200 OK (access token redacted)
- [ ] 04-profile-no-token-401.png — GET /api/profile (no token) → 401 Unauthorized
- [ ] 05-profile-with-token-200.png — GET /api/profile (Bearer token) → 200 OK
- [ ] 06-invalid-login-422.png — POST /api/login (wrong password) → 422 Unprocessable Content
- [ ] 07-logout-200.png — POST /api/logout (Bearer token) → 200 OK
- [ ] 08-revoked-token-401.png — GET /api/profile (revoked token) → 401 Unauthorized

## Reflection

A `401 Unauthorized` response means the request lacks valid authentication credentials — the protected `/api/profile` endpoint rejects it because no Sanctum Bearer token was sent (or the token was revoked). A successful authenticated profile response returns `200 OK` with the authenticated user's `id`, `name`, and `email`, because the `auth:sanctum` middleware validated the Bearer token and resolved `$request->user()`. After logout, the current access token is deleted/revoked, so reusing that same token on `/api/profile` returns `401 Unauthorized` again.

> Note: real access tokens, passwords, and `.env` secrets are intentionally excluded from this repository and from the screenshots.
