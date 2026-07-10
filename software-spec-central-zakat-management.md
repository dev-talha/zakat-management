# Software Requirement Specification for a Bangladesh Central Zakat Management Platform

## Strategic intent and Bangladesh rationale

This specification defines a **central, enterprise-grade Zakat Collection and Distribution Management Platform** for Bangladesh. The platform is intended for a national or central zakat authority, Islamic foundation, NGO, Islamic charity, mosque network, or private zakat institution that needs to collect funds digitally, calculate zakat in a Shariah-compliant way, verify beneficiaries rigorously, distribute assistance through controlled workflows, and publish trustworthy reports without exposing beneficiary privacy. The core business goals are transparency, Shariah compliance, donor confidence, beneficiary dignity, fraud prevention, auditability, and measurable impact. Bangladesh has both a state-linked zakat structure under the Islamic Foundation and an active NGO and mosque ecosystem; at the same time, the country continues to face large-scale poverty and vulnerability challenges, which makes disciplined targeting and transparent fund governance especially important. citeturn34search1turn36search1turn36search2turn23search0turn23search2

Bangladesh has a strong institutional basis for digitizing zakat. The current public eZakat presence operated under the Zakat Board/Islamic Foundation is already centered on awareness, calculation, login, and payment, and it prominently publishes zakat mas’alah content, nisab guidance, distribution policy, collection policy, debt treatment, ineligible spending guidance, a privacy policy, and support/contact information. The Islamic Foundation’s broader public site separately exposes Zakat Fund policy materials, Zakat Board pages, and bank-account information for zakat deposits. In other words, Bangladesh already has the beginnings of a digital public zakat interface, but the visible public stack is far more collection- and information-oriented than it is beneficiary-verification-, case-management-, and audit-automation-oriented. This proposed system therefore preserves the strengths of the current public eZakat model while extending it into a full operational backbone. citeturn53search0turn52search0turn44search0turn33search2turn48search0turn48search1

Bangladesh also has the right digital rails for scale. bKash’s business platform supports payment gateway, tokenized checkout, subscription payments, instant refunds, B2C/B2B-style business capabilities, APIs, merchant QR, and 24/7 collection; Nagad exposes donation and merchant-pay features and states that customer onboarding can be performed through NID scanning, selfie, signature, and PIN creation; Rocket and DBBL support merchant payment, salary/allowance-style disbursement, mobile banking, and internet payment gateway services; SSLCOMMERZ and ShurjoPay both provide merchant integration, sandbox environments, IPN/webhook flows, and QR/invoice options. This means a Bangladesh zakat platform can be built around familiar domestic payment instruments rather than forcing donor behavior into foreign rails. citeturn20search3turn20search5turn20search10turn21search0turn21search1turn21search3turn22search0turn22search1turn22search2turn22search3turn38search0turn38search3turn38search5turn19search11turn19search13

A Bangladesh deployment should assume the following target users from day one: donors, zakat officers, collection officers, finance administrators, field agents, supervisors, Shariah board members, auditors, branch managers, mosque committees, compliance officers, support teams, public viewers, and beneficiaries themselves. The e-service posture of Bangladeshi public bodies also suggests that **Bangla-first**, mobile-friendly, and low-bandwidth design is non-negotiable: government portals such as the Islamic Foundation, NGO Affairs Bureau, and the Bangladesh NID system already expose Bangla interfaces and accessibility-oriented controls, while major payment ecosystems are mobile-native and mass-market. citeturn33search2turn37search7turn8search6turn20search5turn21search9

### Target user model

| User group | Primary objective in the platform | Typical channels |
|---|---|---|
| Donors | Calculate zakat, donate, view receipts, track impact | Web, mobile web, payment links, QR |
| Zakat collectors and collection officers | Register donations, reconcile collections, run campaigns | Admin panel, branch panel |
| Beneficiaries | Apply, upload documents, track status, receive assistance | Bangla portal, SMS/OTP login |
| Field agents and supervisors | Verify need, capture evidence, approve/reject field reports | Mobile-first web app, offline forms |
| Finance and compliance teams | Manage ledgers, approvals, reconciliations, audits | Admin/finance console |
| Shariah board | Review policies, fatwa mappings, distribution rules | Shariah console |
| Auditors | Inspect logs, reconciliations, hashes, reports | Read-only audit workspace |
| Mosque committees and branch managers | Operate local collection/distribution under central controls | Branch portal |
| Public viewers | Verify aggregate transparency, not personal data | Public transparency site |
| Government or regulator-facing stakeholders | Review compliance exports and structured reports | Secure export/API/reporting |

### Bangladesh problem statement that the software must solve

The Bangladesh zakat and charity landscape is still burdened by fragmented practices. Recent research on zakat management in Bangladesh highlights weak public awareness, inefficiency, lack of transparency, and structural weaknesses in collection and distribution; other work on Bangladeshi NGO accountability finds beneficiary participation weakest in decision-making and evaluation compared with implementation. A production zakat platform in Bangladesh should therefore be designed not merely as a payment site, but as a **governance machine**: one that reduces duplicate beneficiaries, documents why decisions were made, enforces double review, captures follow-up outcomes, and publishes clean, privacy-safe transparency data that donors can actually verify. citeturn45search0turn45search1turn45search6turn45search8

## Shariah, legal, and governance baseline

### Shariah operating model

The platform must treat zakat as a **restricted religious fund** with stricter controls than general donations. The current eZakat content and widely used zakat guidance both align around the core principles that zakat becomes due when wealth reaches nisab, that many commonly held liquid and trade-related assets are zakatable, and that the commonly applicable rate for cash-like wealth and trade assets is 2.5%. Global zakat references commonly express nisab around the value of approximately 87.48 grams of gold or 612.36 grams of silver, while the Bangladesh eZakat site publishes its own local nisab chart and rules content; accordingly, the software must not hardcode a single universal number, but instead must support **board-configurable nisab bases**, daily metal-rate refresh, and versioned scholarly rule sets. citeturn24search0turn24search5turn24search11turn53search0

The zakat calculator must support, at minimum, the following configurable asset classes: cash on hand, bank deposits, savings products, shares/investment certificates, trade inventory, receivables expected to be recovered, gold, silver, business working capital, rental-income balances held as savings, and institution-specific custom assets. The eZakat site specifically highlights cash/bank balance, shares, prize bonds/certificates, debts owed to the payer, and exemptions for personal residence, household-use items, riding/working animals, and cultivation-related use assets. Because agricultural produce and livestock follow different fiqh treatments than ordinary cash-like assets, the calculator architecture should use a rule engine rather than a flat 2.5% formula for every asset class. citeturn53search0turn52search0turn26search16turn26search12

Recipient distribution must be locked to the eight zakat categories identified in Qur’an 9:60 and echoed by current eZakat content. The platform should therefore maintain two separate concepts: **donor preference** and **Shariah eligibility category**. A donor preference such as “orphan support,” “medical,” or “education” is not by itself enough to make a payment zakat-compliant; the actual recipient must still be mapped to one of the eligible zakat heads. If that mapping cannot be made, the system must either block the zakat allocation or automatically route the contribution to a non-zakat fund such as sadaqah, emergency relief, or general charity, depending on donor consent and institutional policy. citeturn25search1turn24search3turn52search0

The system must also preserve the distinction among **zakat, sadaqah, zakat al-fitr/fitrah, waqf, general donation, and emergency relief**. These are not interchangeable ledgers. Zakat must remain segregated, and the eZakat site explicitly states that zakat expenditure must remain within Shariah-compliant heads and should be used for poverty alleviation, self-reliance, rehabilitation, and related approved purposes; it also explicitly lists certain ineligible recipients and spending constraints. The software must therefore enforce ledger-level separation, policy-driven allocation rules, and mandatory exception logging whenever an operator attempts to cross-fund or reclassify money. citeturn36search1turn52search0turn53search0

### Bangladesh legal and institutional baseline

The legal center of gravity for a Bangladesh central zakat deployment is the **Zakat Fund Management Act, 2023**. Publicly available Bangladesh law records show that the Act replaced the earlier 1982 ordinance, establishes the zakat board and fund framework, includes provisions on board functions, committee formation, budget, accounts and audit, reporting, and expressly prohibits expenditure of zakat outside Shariah-compliant heads. That means the platform must be able to model not only donor and beneficiary data, but also the institutional mechanics of boards, committees, fund spending, reports, and audit records. citeturn34search1turn34search8turn36search0turn36search1turn36search2turn36search6

If the operating institution is an NGO handling foreign donations or mixed-source humanitarian funding, the platform also needs an **NGO Affairs Bureau compliance overlay**. NGOAB’s official instructions require registration-time and proposal-time records such as FD-1 and FD-6 forms, executive committee member lists, NID copies, constitutions, activity reports, plans of operation, donor commitment letters, treasury challans, and audit arrangements, and NGOAB publicly maintains enlisted audit-firm references and complaint systems. The platform should therefore support entity-type-aware compliance packs so that a mosque-only institution is not burdened with NGOAB-only forms, while an NGOAB-registered operator can store and export everything needed for bureau interactions. citeturn10search2turn10search6turn37search1turn37search2turn37search3turn37search5

Bangladesh’s **Personal Data Protection Act, 2026** materially changes system requirements. Public law records show that the Act includes lawful bases for processing, special conditions for sensitive personal data, rights of access/portability/correction, retention conditions, breach provisions, data-audit obligations, data-protection planning, and the role of a Chief Data Officer. In parallel, the **Cyber Security Ordinance, 2025** provides the broader cyber-security frame. For this reason, the zakat platform must be designed around explicit consent capture, data minimization, retention schedules, breach workflows, role-based access, encryption, and privacy-safe public reporting from the outset rather than bolting them on later. citeturn50search2turn50search4turn49search2turn49search0

On KYC and AML, the platform should align with Bangladesh’s **e-KYC and suspicious-activity reporting direction**. BFIU’s public materials state that Bangladesh issued e-KYC guidance beginning in January 2020, that the aim is secure digital onboarding and stronger AML/CFT compliance, and that BFIU receives and analyzes STRs/SARs/CTRs. The platform therefore needs suspicious-transaction review, donor-risk flags, beneficiary-risk flags, manual escalation, and exportable investigation trails—even if the zakat institution itself is not a formal reporting organization under every law. This is prudent governance, and for some institutions it may become a regulatory necessity. citeturn46search2turn46search6turn47search0turn47search2turn47search4turn47search11

### Bangladesh identity, verification, and communications baseline

Identity verification in Bangladesh can be grounded in a layered model. The Bangladesh Election Commission’s NID system is publicly accessible for account registration and document status checks; the birth and death verification system is public; and Porichoy publicly describes itself as the official Bangladesh identity lookup/e-KYC service with NID and face-match capabilities. Mobile number trust can be improved through OTP confirmation and, where contracts permit, by checking line ownership or relying on regulation-backed SIM biometric ecosystems. BTRC also maintains directives and listings for A2P SMS aggregators, which matters for notification compliance and delivery reliability. citeturn8search6turn8search7turn9search2turn8search9turn41search0turn41search2

### Mandatory eZakat content incorporation

The new platform should include an **authoritative Shariah content layer** based on the current eZakat information architecture. At minimum, the content-management subsystem should ingest and publish curated, board-approved versions of the following materials already visible on eZakat: zakat basics and mas’alah, current nisab guidance, asset exemptions, eight recipient categories, debt-related rulings, timing rules, distribution policy, collection policy, ineligible heads, support contact details, and privacy policy. These should be version-controlled, bilingual, and linked to operational rules so that staff are always looking at the same policy set the system is actually enforcing. citeturn52search0turn53search0turn44search0turn48search1

### Fund types and rule classes

| Fund type | Core rule | Collection allowed | Distribution constraint |
|---|---|---|---|
| Zakat | Restricted religious fund | Yes | Only Shariah-eligible recipients and approved heads |
| Sadaqah | Flexible charity fund | Yes | Wider discretion, still policy-controlled |
| Fitrah / Zakat al-Fitr | Seasonal religious fund | Yes | Time-bound and board-controlled |
| Waqf | Endowment-linked fund | Yes | Managed according to waqf deed and governance rules |
| Emergency relief | Humanitarian fund | Yes | Use for urgent cases; if funded by zakat, recipient must still be zakat-eligible |
| Admin / operations | Non-zakat operational fund | Yes, if disclosed | Never mixed invisibly with zakat |
| Restricted program funds | Purpose-tagged donations | Yes | Only to designated program or approved substitution policy |
| Unrestricted general donation | Flexible institutional fund | Yes | Board-approved general charitable use |

## Functional product specification

### Donor, campaign, and collection domain

The **Donor Management** module should support registration and login for individual, corporate, mosque, branch, and institutional donors; KYC status; zakat calculator; donor wallet/history; recurring pledges; anonymous and named donations; downloadable receipts; impact updates; and preference-based giving. The product rule that matters most is this: donor preference must be captured, but the allocation engine must still enforce fund restrictions and Shariah category mapping before any zakat disbursement is approved. The donor dashboard should therefore show not just “where you intended to give,” but also “how the institution validly classified and used your fund.” This is how donor trust is built in a regulated, auditable way. The current Bangladesh market supports this design because the local payment ecosystem already offers recurring, merchant, API, refund, and QR capabilities across bKash, SSLCOMMERZ, and ShurjoPay. citeturn20search3turn20search10turn38search0turn38search3turn38search5turn19search11turn19search13

The **Zakat Collection** module should accept cash, bank transfer, manual deposit, cheque, MFS, card, POS, payment link, and QR channels. Adapters should be built for bKash, SSLCOMMERZ, and ShurjoPay first, with Nagad, Rocket/DBBL, and direct bank API or file-based reconciliation as wave-two integrations depending on partner approvals. SSLCOMMERZ is particularly useful as a domestic aggregator because its official documentation supports sandbox/live environments, IPN, risk flags, donation categories, and invoice/QR flows; ShurjoPay similarly supports authentication, initiate-payment, verify-transaction, and IPN-style event handling. citeturn38search0turn38search1turn38search3turn38search5turn19search11turn19search13

The **Public Website and Donor Portal** should preserve what donors already expect from eZakat-style public services: a home page, “About Zakat” educational content, calculator, campaign pages, online donation journey, donor login, receipt download, privacy-safe transparency dashboard, annual reports, and frequently asked questions. Bangladesh examples already show a strong expectation for public transparency and donation certificates: the Bangladesh Red Crescent donation portal states that every donation is tracked, audited, and reported, and that tax-rebate eligible certificates are provided under Bangladesh income tax law where applicable. That makes a configurable **tax/compliance receipt engine** a practical product requirement, though its actual activation must depend on the legal status of the operating institution. citeturn53search0turn45search11turn10search1

### Beneficiary, field, and case domain

The **Beneficiary Management** module should be household-centric, not person-centric. In Bangladesh, fragmented aid systems frequently fail because one person is tracked while household need, dependency ratio, disability burden, debt, and housing precarity remain invisible. Each beneficiary profile should therefore support household composition, geolocation, ward/upazila/district tagging, NID or alternate ID, mobile verification, income, assets, liabilities, medical status, disability, education, housing, employment, vulnerability scoring, zakat-category tagging, document upload, blacklist/watchlist flags, duplicate confidence score, and lifecycle status. Identity verification should be layered: NID where available, birth certificate where appropriate, alternate documentation for the undocumented, and explicit exception workflows for vulnerable people who cannot pass normal digital KYC. citeturn8search6turn8search7turn9search2turn46search2

The **Field Agent / Human Verification** module is not optional; it is the heart of fraud prevention and dignity-sensitive service delivery. Field agents should be onboarded centrally, assigned to defined geographic areas, and given mobile-first verification forms that capture household interview data, photos, documents, GPS coordinates, timestamps, visit notes, and fraud suspicion flags. Offline collection with later sync should be implemented as a progressive web application pattern so that low-connectivity field work remains possible. Because eZakat’s own collection and distribution policy materials emphasize committee-controlled collection targets and controlled, approved distribution, the verification module should also support double-check workflows and supervisor countersign-off before a case becomes payable. citeturn52search0turn53search0turn37search7

The **Case Management** layer should sit above beneficiary records. Every assistance decision should generate a case with priority, requested intervention type, supporting evidence, timeline, approvals, notes, follow-up schedule, and outcome. This matters because not every household should be handled as a one-off transaction. Some will require food support, some medical grants, some debt relief, some education continuity, and some livelihood rehabilitation. Cases should therefore remain active across assessment, approval, disbursement, acknowledgment, follow-up, and graduation so that the institution can answer the question donors often ask but legacy tools cannot: *what happened after the transfer?*

The **Beneficiary Portal** should be Bangla-first, low-bandwidth, OTP-based, and forgiving. It should allow submission of applications, supporting documents, appointment scheduling, status checks, and complaints. It should also explicitly explain that submission does not guarantee eligibility, because the final decision requires human review and Shariah/finance approval. Since major Bangladesh public-service systems already expose Bangla interfaces and mobile-centric onboarding, there is no reason for a central zakat system to be desktop-centric or English-only. citeturn8search6turn37search7turn33search2

### Fund, distribution, governance, and public-trust domain

The **Fund Management** module must implement separate ledgers for zakat, sadaqah, fitrah, waqf, emergency relief, admin, and any restricted donor pool. It should support chart of accounts, branch accounting, allocation rules, budget planning, approval chains, bank reconciliation, cash-in-hand monitoring, and immutable audit trails. The legal reason is straightforward: the Zakat Fund Management Act, 2023 explicitly contemplates the board, committees, the fund, expenditure, budget, accounts, audits, and reports, and prohibits non-Shariah uses of zakat. The operational reason is equally strong: mixed-use charity ledgers are one of the fastest ways to lose donor trust and create audit exposure. citeturn36search0turn36search1turn36search2

The **Distribution Management** module should support cash grants, bank transfers, MFS disbursement, vouchers, food packs, medical aid, education support, debt relief, livelihood assets, housing support, and emergency assistance. However, the software must separate the **instrument of help** from the **Shariah basis of help**. For example, “livelihood asset distribution” may be funded from zakat only when the recipient is within a valid zakat category and the board’s policy permits such rehabilitation-oriented use. The current eZakat distribution policy already speaks in terms of poverty alleviation, employment, self-reliance, rehabilitation, one-time non-returnable support, and delivery through cash, cheque, or materials. That should be turned into configurable disbursement-policy templates, not left in PDF form. citeturn52search0turn53search0

The **Admin Panel** must unify super-admin governance, roles and permissions, branches, mosques, collection centers, field agents, donor operations, beneficiary operations, fund approvals, AI settings, blockchain settings, report design, notification templates, language settings, payment gateway settings, and security controls. This is the main operational cockpit. It should also include an institutional settings area for Shariah rule packs, fatwa documents, approval thresholds, and Ramadan-season campaign defaults.

The **Reporting and Analytics** domain should include collection reports, distribution reports, donor reports, beneficiary reports, area-wise need maps, campaign performance, fund utilization, pending approvals, AI-risk views, audit reports, blockchain verification summaries, and impact dashboards. Bangladesh’s digital public-service direction supports map-based and district-oriented analytics: NGOAB’s e-service portal publicly displays area-based SDG presence and beneficiary demographics, suggesting that geographic/public overlays are already becoming normal in Bangladesh civic platforms. citeturn37search7

The **Notification and Communication** layer should use email, SMS, and optional WhatsApp, with future room for push notifications. For Bangladesh, SMS remains operationally critical; BTRC maintains directives and listings around A2P SMS aggregators, so the implementation should use approved local providers rather than ad hoc gateways. Templates must be bilingual, parameterized, and role-aware. citeturn41search0turn41search2

The **Complaint, Feedback, and Grievance** module should support donor complaints, beneficiary complaints, anonymous reports, fraud alerts, SLA clocks, ticket assignment, escalation, and resolution audit logs. This is not merely a nice-to-have. Bangladesh public agencies already surface online complaint and grievance paths, and a zakat institution that cannot hear and log complaints will struggle to justify its transparency claims. citeturn37search3turn33search2

The **Audit and Compliance** module should record user activity, approval histories, payment events, ledger adjustments, distribution acknowledgments, blockchain anchors, and export packages for internal and external audit. Because NGOAB publicly maintains enlisted audit-firm references and the Zakat Fund Management Act expects accounting and audit discipline, the platform should assume that every critical transaction may be reviewed later by finance, Shariah, management, external auditors, or regulators. citeturn37search1turn36search2

### Intelligence and transparency layers

The **AI Assistant Module** should support OpenAI, Google Gemini, Anthropic Claude, and Ollama through a provider abstraction layer. OpenAI, Gemini, and Anthropic all publish official API documentation, while Ollama explicitly positions itself for open-model automation and states that it can run entirely offline with “your data stays yours.” For a Bangladesh zakat platform, that means the system should support both cloud AI and local/offline AI, with policy-based routing so that highly sensitive beneficiary data can stay within local environments when required. AI use cases may include OCR assistance, Bangla-English translation, application summarization, duplicate detection support, agent-visit note summarization, donor communication drafting, anomaly detection, and case recommendations—but never final Shariah, eligibility, financial, or disbursement decisions without human approval. citeturn14search0turn14search1turn15search1turn14search3turn14search11

The **Blockchain Transparency Module** should use a hash-anchoring model rather than on-chain personal records. Ethereum’s own documentation emphasizes that data-storage choices on-chain involve material cost and tradeoffs, and that privacy on public chains is a real concern. Ethereum also identifies Sepolia as the recommended default application-development testnet. For future private or consortium deployments, Hyperledger Besu officially supports permissioned Ethereum-compatible networks with permissioning and PoA consensus options such as QBFT and IBFT 2.0. Therefore, the recommended blockchain design is: store full operational data off-chain in the application database; generate canonical hashes for donation receipts, disbursement batches, and report manifests; post only hashes and minimal metadata on-chain; and expose a public transparency explorer that verifies integrity without revealing beneficiary identities. citeturn16search1turn16search2turn16search6turn16search10turn17search1turn17search14turn17search11

### Consolidated module matrix

| Module family | Included modules | Core outcome |
|---|---|---|
| Donor and collection | Donor Management, Zakat Collection, Public Website/Donor Portal, Notifications | Frictionless and trustworthy donation journey |
| Beneficiary and field operations | Beneficiary Management, Field Agent Verification, Case Management, Beneficiary Portal, Agent Mobile/Web App | Verified, document-backed targeting and follow-up |
| Finance and disbursement | Fund Management, Distribution Management, Reporting, Audit and Compliance | Controlled fund segregation and auditable payout |
| Governance and public trust | Admin Panel, Complaint/Grievance, Public Transparency, Shariah workflows | Institutional accountability and legitimacy |
| Intelligence and integrity | AI Assistant, Blockchain Transparency | Better productivity and stronger public verification |

## Roles, workflows, and user experience

### Role and permission matrix

| Role | Key permissions |
|---|---|
| Super Admin | Full system authority, policy packs, role assignment, integration secrets, emergency controls |
| System Admin | Application configuration, branches, users, security settings, notification templates |
| Finance Admin | Ledgers, reconciliations, approvals, disbursement release, financial reports |
| Zakat Officer | Case review, eligibility routing, fund-category assignment, distribution recommendations |
| Shariah Board Member | Rule-pack review, fatwa references, exception approval, policy sign-off |
| Auditor | Read-only access to logs, reconciliations, reports, blockchain verification screens |
| Branch Manager | Branch-level users, collections, local approvals, performance monitoring |
| Collection Officer | Register and reconcile donations, cash/bank/manual entries, campaign management |
| Distribution Officer | Create batches, prepare disbursements, track failures, confirm acknowledgments |
| Field Agent | Conduct visits, upload evidence, update case notes, request escalation |
| Supervisor | Review field work, countersign verification, schedule revisits, monitor agents |
| Donor | Register, calculate zakat, donate, view receipts/history/impact |
| Beneficiary | Apply, update documents, track status, receive notifications, complain |
| Customer Support | View tickets, respond to donors/beneficiaries, escalate issues |
| Data Entry Operator | Enter back-office records under maker-checker controls |
| Compliance Officer | Review KYC, AML flags, suspicious patterns, export compliance packs |
| Public Viewer | View public aggregate dashboards and hash verification only |

### Critical workflow specifications

The platform should implement the following primary operational flows.

```text
Donor registration to receipt
Donor -> Register/Login -> Verify mobile/email -> Optional KYC -> Run calculator ->
Select fund/campaign -> Create payment session -> Gateway callback/IPN ->
Payment validation -> Receipt issue -> Ledger post -> Donor notification
```

```text
Zakat calculator to payment
Asset input -> Rule-pack selection -> Nisab basis -> Liability deduction ->
Zakat due calculation -> Donor confirmation -> Checkout session ->
Gateway confirmation -> Receipt + donation certificate + donor dashboard update
```

```text
Beneficiary application to approval
Application submit -> OTP/doc intake -> Pre-screen validation ->
Duplicate/risk screening -> Case creation -> Field assignment ->
Visit report -> Supervisor review -> Shariah/finance decision ->
Approved / Rejected / Awaiting more information
```

```text
Agent verification to supervisor approval
Case assigned -> Home visit -> GPS/photo/docs/interview -> Offline sync if needed ->
Agent submits report -> Second review/supervisor check ->
Return for clarification or approve for case committee
```

```text
AI risk check to human decision
Structured case data -> AI summarization/risk suggestion ->
Explainability note + confidence -> Human reviewer compares evidence ->
Accept / modify / ignore AI suggestion -> Reason logged
```

```text
Fund allocation to distribution
Approved cases -> Fund eligibility engine -> Batch proposal ->
Finance check -> Shariah review if required -> Final approval ->
Disbursement generation -> Release to payout channel
```

```text
Distribution confirmation to follow-up
Payment/material release -> Beneficiary acknowledgment (OTP/sign/photo/manual) ->
Failed delivery queue/retry -> Follow-up schedule -> Outcome update ->
Graduation or continued support decision
```

```text
Blockchain hash creation
Finalized internal record -> Canonical JSON payload -> Hash generation ->
Multisig approval queue -> On-chain anchor post ->
Transaction hash stored internally -> Public explorer refresh
```

```text
Public transparency verification
Public user enters receipt number or hash -> Explorer fetches off-chain metadata ->
System recomputes hash -> Compares with on-chain record ->
Displays "verified / mismatch / pending anchor"
```

```text
Audit review
Auditor selects period -> Reviews collections, ledgers, batches, logs, hash anchors ->
Exports evidence pack -> Files findings -> Management response -> Closure tracking
```

```text
Complaint resolution
Complaint intake -> Category + SLA -> Assigned owner ->
Investigation + evidence capture -> Resolution draft ->
Escalation if overdue/high-risk -> Complainant notification -> Closure audit log
```

### UI and UX requirements

The user interface should be built on a **Bootstrap 5** responsive system and optimize for mobile-first access. Bootstrap’s current major release line is 5.x, and Laravel remains comfortable with Blade-based full-stack rendering, which is appropriate for a compliance-heavy transactional system where form reliability and back-office productivity matter more than SPA novelty. Bangladesh government portals also already expose accessibility controls such as font resizing, monochrome/invert options, link highlighting, and screen-reader support; the zakat platform should adopt those patterns, especially on the beneficiary portal and public site. citeturn13search15turn43search4turn33search2

The public and beneficiary UX should prioritize the following principles: Bangla-first copywriting with English toggle, plain-language status labels, large touch targets, lightweight pages, resumable forms, SMS-linked deep links, and assisted-service flow for low-literacy users. The donor checkout UX should be short, confidence-building, and explicit about fund type, fees, and receipt status. The beneficiary UX should avoid stigma: terms like “rejected” should always be paired with a structured explanation or a request-for-more-information path.

### Dashboard requirements

| Dashboard | Minimum widgets |
|---|---|
| Super Admin | Total collections, pending approvals, branch performance, fraud alerts, uptime, AI usage, blockchain anchor status |
| Finance Team | Daily collections, reconciliations, unreconciled payments, fund balances, disbursement queue, failed payouts |
| Shariah Board | Cases pending Shariah review, rule exceptions, fatwa references used, fund-mix alerts |
| Field Agent | Assigned visits, overdue tasks, offline sync status, supervisor comments |
| Branch Manager | Local collections, local approvals, agent productivity, branch disbursement completion |
| Donor | Donation history, current-year zakat, receipts, campaign updates, impact summaries |
| Beneficiary | Application status, required documents, appointment date, helpdesk tickets |
| Auditor | Audit exceptions, ledger digests, batch histories, hash verification reports |
| Public Transparency Portal | Aggregate collections, aggregate distributions, area/campaign summaries, verified report hashes, annual reports |

## Architecture, data, and interfaces

### Recommended technology stack

As of May 2026, **Laravel 13** is the current stable release and supports PHP 8.3–8.5; Laravel’s official documentation also confirms native support patterns for Sanctum, Passport, queues, Horizon, scheduling, authorization, and Blade-centric full-stack development. Bootstrap’s current major release is 5.x. The recommended production baseline is therefore PHP 8.3+, Laravel 13.x, Blade + Bootstrap 5, MySQL or PostgreSQL, Redis, object storage, and Nginx on Linux. Laravel 12 remains security-supported until February 24, 2027, so highly conservative institutions could use it as a fallback, but a new implementation started now should target 13.x. citeturn43search1turn43search3turn55search0turn55search1turn55search16turn40search0turn40search1turn40search2turn13search15turn13search10

| Layer | Recommended baseline |
|---|---|
| Backend | PHP 8.3+, Laravel 13.x, Composer packages under enterprise review |
| Auth | Laravel Sanctum by default; Laravel Passport only when OAuth2 third-party delegation is required |
| Frontend | Blade templates, Bootstrap 5, HTML5, CSS3, JavaScript, optional Vue only for isolated rich widgets |
| Database | PostgreSQL preferred for richer indexing and JSONB; MySQL acceptable if team skill is higher there |
| Cache / queues / sessions | Redis |
| Search | PostgreSQL full-text first; OpenSearch/Elasticsearch optional in later phase |
| AI integration | Provider abstraction for OpenAI, Gemini, Anthropic, Ollama |
| Blockchain | Ethereum Sepolia first; mainnet optional; Besu/private network-ready design |
| File storage | S3-compatible object storage with server-side encryption |
| Web server | Nginx on Linux |
| Observability | Central logs, queue metrics, Horizon, uptime monitoring, error tracking |
| CI/CD | GitHub or GitLab pipelines, automated tests, staged deployment gates |

### Monolith-first architecture

A **modular Laravel monolith** is the correct first architecture. This project is governance-heavy, workflow-heavy, and record-heavy. It needs consistent transactions, uniform audit logs, fast iteration, and deep business rules across modules. Starting with many microservices would multiply operational risk without improving the first 12 months of delivery.

```text
[Public Site / Donor Portal / Beneficiary Portal / Agent PWA / Admin Panel]
                               |
                            Nginx
                               |
                         Laravel Application
      ---------------------------------------------------------------
      | Identity | Donors | Beneficiaries | Cases | Funds | Reports |
      | Payments | Notifications | AI Orchestrator | Blockchain Hub |
      ---------------------------------------------------------------
               |                 |                |             |
         PostgreSQL/MySQL      Redis        Object Storage   Optional RPC / AI endpoints
```

The monolith should still be **service-ready by design**. Payment adapters, notification senders, AI provider calls, and blockchain writers should be encapsulated behind internal interfaces so they can later be moved into separate services if scale, licensing, or regulatory segregation requires it.

### Module architecture and service boundaries

```text
Core Domain Modules
- Identity and Access
- Donor and Campaigns
- Beneficiary and Households
- Verification and Cases
- Fund Accounting
- Distributions and Acknowledgments
- Reporting and Analytics
- Complaints and Support

Cross-Cutting Services
- Payment Adapter Layer
- Notification Layer
- AI Provider Layer
- Blockchain Anchor Layer
- Audit/Event Log Layer
- File/Document Security Layer
```

### AI service architecture

```text
Laravel AI Orchestrator
    -> Prompt Registry
    -> Provider Selector
        -> OpenAI Adapter
        -> Gemini Adapter
        -> Anthropic Adapter
        -> Ollama Adapter
    -> Usage Metering
    -> Cost Limits
    -> Human Approval Queue
    -> AI Audit Log
```

The orchestrator should assign each task an **AI sensitivity classification**. Example: donor communication draft may be auto-generated and reviewed later; beneficiary risk recommendation must never auto-decide; OCR extraction may prefill fields but must remain editable; translation may be shown with “machine-assisted” labels. Provider fallback is required so that cloud outage or budget exhaustion does not halt core operations.

### Blockchain architecture

```text
Internal finalized record
    -> Canonical serialization
    -> SHA-256 hash
    -> Multisig approval queue
    -> Ethereum/Besu writer
    -> On-chain tx hash saved internally
    -> Public verification API/explorer
```

The smart-contract layer should stay intentionally simple. Recommended contract families:

| Contract | Purpose |
|---|---|
| DonationAnchorRegistry | Stores hash of finalized donation receipt/event manifest |
| DistributionBatchRegistry | Stores hash of approved batch manifest |
| ReportManifestRegistry | Stores hash of published audit/annual report artifacts |
| GovernanceConfig | Stores approved signing roles and pause state |

No beneficiary PII, documents, NID numbers, or mobile numbers should ever be written on-chain. The platform must support an “off-chain only” mode in case blockchain is disabled by policy or cost constraints.

### Payment and notification architecture

```text
Checkout request
 -> Payment adapter
 -> Gateway session creation
 -> Customer redirect / QR / payment link
 -> Callback + IPN/Webhook
 -> Validation API
 -> Reconciliation queue
 -> Ledger posting
 -> Notification dispatch
```

SMS and email dispatch should be asynchronous and idempotent. Gateway-originating callbacks should never directly mutate business state without validation. A payout adapter pattern should be used for distribution channels, with strict state models such as `initiated`, `submitted`, `accepted_by_provider`, `settled`, `failed`, `reversed`, and `manually_resolved`.

### Database design proposal

The platform should use a normalized operational schema plus an append-only event/audit layer. Sensitive columns should be encrypted at the application level where feasible and at the database/storage level by default.

| Table | Core fields |
|---|---|
| users | id, name, email, mobile, password_hash, status, locale, last_login_at |
| roles | id, code, name |
| permissions | id, code, name |
| role_user / permission_role | role/user or permission/role join fields |
| donors | id, user_id, donor_type, display_name, legal_name, tax_id, anonymous_default, kyc_status |
| donor_addresses | donor_id, country, division, district, upazila, address_line |
| beneficiaries | id, primary_person_name, gender, dob, identity_type, identity_no, mobile, status, vulnerability_score |
| beneficiary_households | id, beneficiary_id, address, geo_lat, geo_lng, district, upazila, ward, housing_type |
| household_members | household_id, name, relation, age, disability_flag, education_status |
| beneficiary_documents | beneficiary_id, doc_type, file_path, verification_status, verified_at |
| verification_visits | case_id, agent_id, visit_at, gps, summary, risk_flag, supervisor_status |
| agents | id, user_id, branch_id, area_code, onboarding_status, capacity_score |
| cases | id, beneficiary_id, case_type, priority, stage, source, requested_amount, outcome_status |
| case_notes | case_id, author_id, note_type, body |
| approvals | entity_type, entity_id, step_name, approver_id, decision, reason, decided_at |
| zakat_calculations | donor_id, rule_pack_id, nisab_basis, asset_snapshot_json, liability_snapshot_json, zakat_due |
| campaigns | id, name, fund_type, branch_id, target_amount, starts_at, ends_at, status |
| collections | id, donor_id, campaign_id, fund_type, source_channel, amount, currency, status |
| payments | collection_id, gateway_id, provider_ref, tran_id, callback_status, validated_status, risk_level |
| payment_gateways | id, code, name, mode, config_json, active |
| funds | id, code, type, restricted_flag, branch_scoped_flag |
| fund_ledgers | id, fund_id, entry_type, debit, credit, ref_type, ref_id, effective_at |
| distributions | id, beneficiary_id, case_id, fund_id, category_code, approved_amount, status |
| distribution_batches | id, branch_id, fund_id, total_amount, approval_status, payout_channel |
| disbursements | batch_id, distribution_id, payout_ref, amount, provider_status, acknowledged_at |
| disbursement_acknowledgments | disbursement_id, method, otp_ref, signature_file, photo_file, remark |
| ai_requests | id, provider, model, task_type, prompt_version, subject_type, subject_id, token_usage, cost_estimate |
| ai_risk_scores | case_id, score, factors_json, explanation, approved_by, approved_at |
| blockchain_records | entity_type, entity_id, hash, chain, network, tx_hash, anchor_status |
| smart_contract_events | blockchain_record_id, contract_name, event_name, block_no, payload_json |
| audit_logs | actor_id, action, subject_type, subject_id, before_json, after_json, ip, user_agent |
| notifications | recipient_type, recipient_id, channel, template_code, payload_json, send_status |
| complaints | complainant_type, complainant_id, channel, category, severity, sla_due_at, status |
| branches | id, code, name, region, status |
| mosques | id, branch_id, name, committee_contact, geo_lat, geo_lng |
| settings | group_code, key, value_json |

#### Relationship model

`users` relates to staff, donors, agents, and possibly beneficiaries for portal access. `beneficiaries` relate one-to-one or one-to-many with `beneficiary_households`, one-to-many with `cases`, `documents`, and `distributions`. `cases` relate to `verification_visits`, `case_notes`, and `approvals`. `collections` produce `payments` and ledger entries; `distributions` roll into `distribution_batches` and `disbursements`. `audit_logs`, `ai_requests`, and `blockchain_records` should support polymorphic subject references across the entire system.

#### Indexing and encryption guidance

Recommended indexes include:

| Pattern | Recommendation |
|---|---|
| Uniqueness | Unique indexes on normalized identity values where legally valid |
| Duplicate detection | Composite indexes on `(identity_type, identity_no)`, `(mobile)`, `(district, geo hash)` |
| Workflow | Indexes on `status`, `approval_status`, `branch_id`, `fund_id`, `created_at` |
| Payment reconciliation | Indexes on `tran_id`, `provider_ref`, `gateway_id`, `validated_status` |
| Audit and forensics | Indexes on `actor_id`, `subject_type`, `subject_id`, `created_at` |
| Public verification | Indexes on `hash`, `tx_hash`, `anchor_status` |

Encrypt or tokenize at minimum: NID/passport/birth-registration numbers, bank or MFS payout identifiers where sensitive, beneficiary address details, document file references, medical data, disability details, AI prompts containing PII, and private blockchain signing data.

### API specification

The platform should be **API-first** even when most screens are Blade-rendered. Recommended base path: `/api/v1`.

| API category | Sample endpoints |
|---|---|
| Auth APIs | `POST /auth/login`, `POST /auth/otp/send`, `POST /auth/otp/verify`, `POST /auth/logout` |
| Donor APIs | `POST /donors`, `GET /donors/me`, `GET /donors/me/collections`, `POST /zakat-calculations` |
| Beneficiary APIs | `POST /beneficiary-applications`, `GET /beneficiaries/{id}`, `POST /beneficiaries/{id}/documents` |
| Payment APIs | `POST /payments/checkout/session`, `POST /payments/callback/{gateway}`, `POST /payments/webhook/{gateway}` |
| Collection APIs | `POST /collections/manual`, `GET /collections`, `POST /collections/{id}/reconcile` |
| Distribution APIs | `POST /distribution-batches`, `POST /disbursements/{id}/retry`, `POST /disbursements/{id}/acknowledge` |
| Agent APIs | `GET /agent/cases`, `POST /agent/visits`, `POST /agent/visits/{id}/submit` |
| AI APIs | `POST /ai/cases/{id}/summarize`, `POST /ai/cases/{id}/risk-score`, `GET /ai/usage` |
| Blockchain APIs | `POST /blockchain/anchor`, `GET /blockchain/receipt/{receipt_no}`, `GET /blockchain/hash/{hash}` |
| Report APIs | `GET /reports/collections`, `GET /reports/distributions`, `POST /reports/custom/export` |
| Admin APIs | `GET /admin/users`, `POST /admin/settings/payment-gateways`, `POST /admin/rule-packs` |
| Notification APIs | `POST /notifications/test`, `GET /notifications/templates`, `POST /notifications/templates` |

#### Sample endpoint example

```json
POST /api/v1/payments/checkout/session
{
  "fund_type": "zakat",
  "campaign_id": 14,
  "amount": 5000.00,
  "currency": "BDT",
  "gateway": "bkash",
  "donor_preference": "medical_support",
  "anonymous": false,
  "calculator_reference_id": 921
}
```

```json
{
  "status": "success",
  "checkout_session_id": "chk_01JV...",
  "redirect_url": "https://gateway.example/redirect/abc",
  "collection_id": 45512,
  "pending_receipt_no": "CZM-2026-00045512"
}
```

```json
POST /api/v1/beneficiary-applications
{
  "applicant_name": "Amina Khatun",
  "mobile": "017XXXXXXXX",
  "identity_type": "nid",
  "identity_no": "XXXXXXXXXX",
  "district": "Kurigram",
  "upazila": "Ulipur",
  "household_members": 5,
  "monthly_income": 6500,
  "assistance_type": "medical",
  "documents": [
    {"type": "nid_front", "token": "file_tok_1"},
    {"type": "medical_report", "token": "file_tok_2"}
  ]
}
```

```json
{
  "status": "submitted",
  "application_no": "BEN-2026-001245",
  "case_id": 9981,
  "next_step": "mobile_otp_verification",
  "message_bn": "আপনার আবেদন গ্রহণ করা হয়েছে। যাচাই প্রক্রিয়া চলমান।"
}
```

```json
POST /api/v1/blockchain/receipt/CZM-2026-00045512/verify
```

```json
{
  "status": "verified",
  "receipt_no": "CZM-2026-00045512",
  "hash": "3c4a...9de1",
  "chain": "ethereum",
  "network": "sepolia",
  "tx_hash": "0xabc...",
  "anchored_at": "2026-05-12T19:11:02+06:00"
}
```

## Security, AI governance, blockchain governance, and operations

### Security requirements

The platform must implement **defense in depth**. Baseline controls should include RBAC, MFA for privileged users, OTP verification for donor/beneficiary mobile flows, strong password policy, session rotation, CSRF protection, XSS and SQL-injection defense, secure file upload validation, malware scanning for documents, rate limiting, field-level encryption, backup encryption, API-secret vaulting, and environment-specific access control. Laravel’s official documentation provides built-in patterns for authentication, authorization via gates and policies, middleware, Sanctum, Passport, queues, and scheduling; these features should be used rather than replaced with ad hoc custom code. citeturn55search1turn55search17turn40search0turn40search1turn55search0turn55search16

Data-protection specifics should map to Bangladesh’s 2026 personal-data law. That means the production design must include: consent records, data-classification rules, retention schedules by entity type, breach logging and response workflows, privacy-safe exports, and cross-border transfer controls for AI providers and cloud infrastructure. Public dashboards must never reveal beneficiary names, full addresses, NID numbers, document images, or any combination of fields that allows household re-identification. citeturn50search2turn49search0turn49search2

### Human touch and double verification

This system should be proudly **human-in-the-loop**. Human verification is required because zakat decisions affect dignity, religious validity, financial trust, and real-world hardship. The required review chain should be:

| Stage | Required reviewer |
|---|---|
| Identity and document plausibility | Data/KYC officer or field agent |
| Household-condition verification | Field agent |
| Field evidence validation | Supervisor |
| Zakat-category and exception review | Zakat officer / Shariah reviewer |
| Fund-availability and release authority | Finance admin |
| High-risk or anomalous cases | Compliance officer and/or audit sampling |
| Final disbursement release | Authorized finance/distribution approver |

Random audit sampling, callback verification, revisit scheduling, beneficiary satisfaction checks, and manual-override reason logging should be built into the operating model. AI may recommend, summarize, and score, but it must not finalize.

### AI governance

AI governance should be policy-driven and logged. Mandatory controls:

| Control | Requirement |
|---|---|
| Human approval | No final eligibility rejection or disbursement approval by AI alone |
| Prompt versioning | Every sensitive AI job tied to a versioned prompt/template |
| Explainability | Risk outputs must include factor explanations, not raw scores only |
| Auditability | Store provider, model, prompt version, token use, cost, human action |
| Data minimization | Mask or exclude unnecessary PII before external model calls |
| Provider fallback | If provider fails, queue or reroute; do not silently skip controls |
| Bias review | Periodic review of outcomes by geography, gender, disability, and income profile |
| Local mode | Ollama/local model path for sensitive OCR, summarization, translation when needed |
| Automation guardrails | No auto-rejection of beneficiaries; no auto-Shariah rulings |

The fastest path to misuse is to let AI create denial decisions that staff cannot explain. The platform should therefore require a reviewer to explicitly choose among “accept recommendation,” “modify recommendation,” or “reject recommendation,” with justification.

### Blockchain governance

Blockchain use should remain a **trust layer**, not a personal-data layer. Governance requirements:

| Topic | Required rule |
|---|---|
| On-chain content | Only hashes and minimal metadata |
| Off-chain content | All full records, PII, documents, and case files |
| Signing authority | Multi-signature or equivalent approval for production anchoring |
| Networks | Sepolia first; mainnet only after governance sign-off |
| Failure fallback | If chain/RPC fails, queue anchors and continue internal operations |
| Reconciliation | Internal record must store canonical hash, anchor status, and tx hash |
| Privacy | Public explorer must show verification state without revealing beneficiary identity |
| Controls | Admin kill switch for blockchain posting; environment-based separation |

Ethereum’s official documentation makes the storage-cost and privacy tradeoffs explicit, and Besu’s permissioning model makes hybrid or consortium deployment feasible later if a government or inter-organization governance model emerges. citeturn16search1turn16search2turn16search6turn16search10turn17search1turn17search14

### Non-functional requirements

| Dimension | Requirement |
|---|---|
| Scalability | Support single authority with many branches first; design for future tenant_id expansion |
| Performance | P95 normal web response under 2 seconds for standard dashboard and CRUD actions |
| Availability | 99.5%+ target for production; higher for payment callback and public verification endpoints |
| Backup | Nightly full backup, frequent incremental backups, tested restore drills |
| Disaster recovery | Defined RPO/RTO, offsite backups, infrastructure-as-code rebuild path |
| Maintainability | Modular domain packages, test coverage, migration discipline, versioned rule packs |
| Localization | Bangla and English, date/number localization, culturally appropriate content |
| Observability | Structured logs, queue monitoring, audit views, uptime alerts |
| Auditability | Immutable audit/event logs for critical actions |
| Privacy | Data minimization, masking, retention expiry, breach process |
| Cost efficiency | Monolith-first, cloud/on-prem optionality, staged external integration use |

## Delivery plan, acceptance, risk, and commercial model

### Phase-wise roadmap

| Phase | Scope |
|---|---|
| Phase 1 MVP | Donor management, beneficiary management, manual collection, one primary gateway, fund ledger, distribution workflow, field verification, basic reports, admin panel |
| Phase 2 | AI assistance, advanced verification, SMS/email automation, public transparency dashboard, advanced reporting, stronger mobile-first agent workflow |
| Phase 3 | Blockchain on Sepolia, smart-contract hash anchoring, advanced fraud detection, better payment reconciliation, audit workspace |
| Phase 4 | Mainnet/premium option, hybrid/private network readiness, multi-branch scaling improvements, advanced analytics, deeper API integrations, optional Android app |
| Phase 5 | National-scale integration, government/NGO interoperability, predictive analytics, federation across institutions, mature public-impact ecosystem |

### Acceptance criteria

| Capability | Acceptance criterion |
|---|---|
| Donor registration | User can register, verify OTP/email, log in, and see dashboard within 3 minutes |
| Payment collection | Gateway callback/IPN validated and reflected in ledger within 2 minutes of success |
| Receipt generation | Digital receipt available immediately after validated payment |
| Beneficiary verification | Case cannot reach approval state without required verification evidence or approved exception |
| Agent double-check | High-risk cases require supervisor countersign before approval |
| Distribution approval | No batch may be released without configured finance approval and, where required, Shariah approval |
| Fund ledger | Every collection and disbursement creates balanced ledger entries and audit events |
| AI recommendation | Every AI high-impact result stores provider, model, prompt version, explanation, and reviewer decision |
| Blockchain hash creation | Finalized donation/distribution record can be hashed and anchored, with tx hash visible internally |
| Public verification | Public user can verify receipt/report hash without seeing beneficiary PII |
| Reports | Finance/audit/export reports available in PDF, Excel, and CSV |
| Audit logs | Critical create/update/approve/release actions are tamper-evident and searchable |
| Security | MFA, rate limiting, encrypted secrets, secure uploads, and backup restore tests pass before go-live |

### Risk analysis and mitigation

| Risk | Likely impact | Mitigation |
|---|---|---|
| Fake beneficiaries | High | NID/Porichoy where available, household-based verification, geo evidence, revisit sampling |
| Duplicate records | High | Deterministic + fuzzy duplicate engine across ID/mobile/household/address |
| Agent corruption or collusion | High | Supervisor review, random audit, GPS/photo proofs, rotation, anomaly flags |
| Payment failure or missed callback | High | IPN/webhook + validation API + reconciliation queue |
| Donor mistrust | High | Public transparency dashboard, downloadable receipts, policy visibility, audit reports |
| Shariah non-compliance | High | Rule packs, Shariah board workflow, fund segregation, exception controls |
| AI wrong recommendation | Medium/High | Human approval, explainability, conservative automation boundaries |
| Data breach | High | Encryption, least privilege, vaulting, logging, breach playbook, retention controls |
| Internal misuse | High | RBAC, maker-checker, audit logs, privileged-access MFA, optional IP restriction |
| Blockchain cost volatility | Medium | Hash-only design, batch anchoring, testnet first, optional disable switch |
| Regulatory change | Medium | Versioned compliance packs, legal review checkpoints, configurable forms and rules |
| System downtime | High | HA hosting, backups, DR drills, graceful manual fallback procedures |

### Deployment plan

A three-environment lifecycle is recommended: **development**, **staging/UAT**, and **production**. Production should run on hardened Linux servers under Nginx, with separate worker processes for queues, scheduled tasks, notifications, imports, and blockchain writers. Containerization with Docker is recommended, but not mandatory, if the operating institution has a stronger traditional Linux ops team.

| Environment | Purpose |
|---|---|
| Development | Active coding, seeded fake data, relaxed throttles, local AI option |
| Staging/UAT | Production-like integrations, masked or synthetic data, user acceptance tests |
| Production | Hardened environment, monitored, encrypted backups, approved gateways only |

Deployment essentials:

| Item | Minimum requirement |
|---|---|
| CI/CD | Automated tests, linting, migrations gated, manual approval for production |
| Backups | Daily database backup, object-storage versioning, monthly restore drill |
| Monitoring | App health, queue depth, failed jobs, disk/memory, cert expiry, gateway callback success |
| Logging | Centralized structured logs with retention policy |
| Rollback | Versioned release artifacts and database rollback playbooks |
| Security hardening | TLS, firewalling, secret rotation, server patch policy, protected admin routes |
| DNS and domains | Separate domains/subdomains for public site, admin, API, and staging |
| Email/SMS | Configurable per environment, with sandbox templates in non-production |

### Development timeline estimate

The following timeline is an **implementation estimate**, not a legal or regulatory fact.

| Delivery slice | Indicative duration |
|---|---|
| Discovery, policy mapping, UX research | 3–5 weeks |
| Architecture, foundations, auth, RBAC, settings | 3–4 weeks |
| MVP business modules | 12–16 weeks |
| UAT, data migration, hardening, training | 4–6 weeks |
| Phase 2 enhancements | 10–14 weeks |
| Phase 3 audit/blockchain/fraud controls | 8–12 weeks |

An institution that makes decisions quickly and uses one payment gateway first can realistically deliver Phase 1 in roughly **5 to 7 months**. A broader multi-branch, multi-gateway, strong-compliance program will take longer.

### Development team recommendation

| Role | Recommended involvement |
|---|---|
| Project Manager | Full-time |
| Business Analyst | Full-time in discovery and UAT-heavy periods |
| Laravel Backend Developer | 2–3 FTE |
| Frontend Developer | 1–2 FTE |
| UI/UX Designer | 1 FTE initially, then part-time refinement |
| QA Engineer | 1–2 FTE |
| DevOps Engineer | 0.5–1 FTE |
| AI Engineer | 0.5–1 FTE |
| Blockchain Engineer | 0.25–0.75 FTE depending on phase |
| Security Consultant | Milestone-based, independent review required |
| Shariah Advisor | Ongoing governance role |
| Finance/Accounting Consultant | Ongoing during ledger/report design |
| Field Operations Consultant | Ongoing for beneficiary and agent workflows |

### Cost category estimate

This estimate is also **indicative** and should be treated as budgeting guidance only.

| Cost category | Indicative share |
|---|---|
| Product discovery, BA, governance design | 8%–12% |
| Backend and database engineering | 22%–30% |
| Frontend and UX | 12%–18% |
| QA and UAT | 10%–15% |
| DevOps, hosting, monitoring | 8%–12% |
| Security review and hardening | 5%–10% |
| AI integration and controls | 5%–10% |
| Blockchain and transparency explorer | 4%–8% |
| Training, rollout, support | 5%–10% |
| Compliance/Shariah/legal review | 4%–8% |

A practical budgeting heuristic for Bangladesh is to estimate **MVP** in the broad range of **BDT 80 lakh to BDT 1.8 crore**, depending on the number of payment integrations, audit depth, field-app complexity, and security review scope. A broader phases 1–3 delivery can rise materially above that range.

### Future enhancement list

Future versions may include national beneficiary deduplication across partner institutions, bank-statement auto-ingestion, face-match verification where legally permitted, speech-to-text for Bangla field notes, predictive poverty analytics, government social-protection data exchange, digital voucher ecosystems, Android native field app, and inter-institutional reporting federations.

## Recommended final position

The recommended product direction is a **Bangladesh-ready, Shariah-governed, Laravel-based modular monolith** that starts from the existing public expectations created by eZakat—education, calculator, login, and payment—but extends them into full beneficiary verification, case management, fund segregation, audit control, public transparency, and impact follow-up. The platform should be **Bangla-first**, **human-verified**, **AI-assisted but never AI-governed**, and **blockchain-anchored without exposing beneficiary privacy**. Local payment methods, local identity verification pathways, Bangladesh legal reforms on data protection and cyber security, NGO/government reporting realities, and the Zakat Fund Management Act, 2023 all point in the same architectural direction: a platform where religious validity, social justice, and digital accountability are treated as one integrated system rather than separate projects. citeturn53search0turn36search1turn36search2turn50search2turn49search2turn20search3turn21search3turn22search2turn38search0turn16search1turn17search1turn43search1