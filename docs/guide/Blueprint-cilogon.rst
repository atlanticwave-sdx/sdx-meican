CILogon Implementation
======================

Authors
-------

- Usman Aziz
- Mahidhar

Issued Date: to be defined

Status: Completed

Type: Standards Track

Abstract
========

This blueprint details how CILogon is integrated with Meican.

Motivation
===========

CILogon adds a second layer of protection for Meican, providing an additional level of security beyond traditional username and password logins. By requiring users to verify their identity through additional means, such as two-factor authentication or digital certificates, CILogon significantly reduces the risk of unauthorized access and data breaches.

Requirements
============

- CILogon integration with Meican.
- New table creation (`meican_cilogon_auth`) for storing the user token, expiration dates, and user IDs.
- Generate and insert a new token with a two-day expiration into the table upon successful login with CILogon.
- Allow access without prompting for CILogon login if the user logs in within the two-day window.
- Prompt for CILogon login again if the user attempts to log in after the expiration period (2 days).
- Creating a migration file for the table `meican_cilogon_auth`.
