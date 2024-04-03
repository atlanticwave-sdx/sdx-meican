:EP: 0
:Title: CILogon Implementation
:Authors:
    - Usman Aziz <>
    - Mahidhar <mgand024@fiu.edu>
:Reviewers:
    - 
    -
:Created: 2024-04-01
:Kytos-Version: 2020.1
:Status: Draft
:Type: Standards Track

*****************************
EP000 - Enhancement Proposals
*****************************

########
Abstract
########
This blueprint details how CILogon is integrated with Meican and the workflow of token authorization.

##########
Motivation
##########

CILogon adds a second layer of protection for Meican, providing an additional level of security beyond traditional username and password logins. By requiring users to verify their identity through additional means, such as two-factor authentication or digital certificates, CILogon significantly reduces the risk of unauthorized access and data breaches.

#########
Rationale
#########
This meta blueprint is intended to make the contributor's workflow more clear about how to write a new blueprint.

#############
Specification
#############

Workflow
**************
Every blueprint should follow the steps below in order to be finished:
 
Outline the subject
===================
The process for writing a blueprint should begin by describing a new idea, a specification to something that has been defined to be implemented. It is recommended that a single blueprint contain a single proposal. The more focused the blueprint, the more successful it tends to be. The Kytos team have the right to reject or approve any proposal. If in doubt, create an issue in the Kytos Project on Github and add a comment asking if the issue could be a blueprint.

Submitting a blueprint
======================
After creating a first blueprint by adding every necessary section (check the sections under "Sections that a blueprint should have") you are encouraged to create a pull request in Kytos repository attending the following criteria:

    - The status of the blueprint should be "draft";
    - The file containing the proposal must follow the naming convention "EP[number].rst", where [number] is a sequential number, e.g. "EP001.rst";

    - The file must contain a header following the template available in Blueprint Header section;

    - The file must be written in restructured text (RST) format like the other blueprints (as you can see at 'kytos/docs/blueprints').


Blueprint review
================
In the following days after you submit your pull request, the Kytos team will review the document adding comments and suggestions. So the author of the pull request must pay attention to the Kytos team feedback to make the review a quick process.

#######################
Backwards Compatibility
#######################
At the moment that this blueprint is being proposed Kytos Project have the total of 21 blueprints created. The standard proposed in EP0 should be followed by any new blueprint proposed after the blueprint 21, and is established here that the update of the previous blueprints are not mandatory.

#####################
Security Implications
#####################
Not applicable here

##############
Rejected Ideas
##############
Not applicable here

##########
References
##########

[1] https://www.python.org/dev/peps/pep-0001/#pep-audience

#########
Copyright
#########

This document is placed in the public domain or under the
CC0-1.0-Universal license, whichever is more permissive.
