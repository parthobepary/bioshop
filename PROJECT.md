# BioShop — Complete Project Documentation

> **Ekta link e apnar bio + products + payment — sob eksathe**

---

## Table of Contents

1. [Overview](#overview)
2. [Problem & Solution](#problem--solution)
3. [Target Market](#target-market)
4. [Features](#features)
5. [User Flows](#user-flows)
6. [Database Schema](#database-schema)
7. [Tech Stack](#tech-stack)
8. [API Endpoints](#api-endpoints)
9. [Page Structure](#page-structure)
10. [UI Components](#ui-components)
11. [Pricing Model](#pricing-model)
12. [Development Roadmap](#development-roadmap)
13. [Marketing Strategy](#marketing-strategy)
14. [Revenue Projections](#revenue-projections)
15. [KPIs & Metrics](#kpis--metrics)
16. [Future Features](#future-features)
17. [Costs & Investment](#costs--investment)
18. [Risks & Mitigation](#risks--mitigation)

---

## Overview

### One-Liner

**BioShop** is a link-in-bio platform with integrated product showcase and payment info — built for Bangladesh.

### Example

```
bioshop.io/hijabhouse
```

### What Makes It Different

| Feature | Linktree | BioShop |
|---------|----------|---------|
| Links | ✓ | ✓ |
| Products | ✗ | ✓ |
| bKash/Nagad | ✗ | ✓ |
| WhatsApp Order | ✗ | ✓ |
| Bangla | ✗ | ✓ |
| BDT Pricing | ✗ | ✓ |

### Position

```
Simple                                    Complex
  |                                          |
Linktree ───→ BioShop ───→ F-commerce ───→ Shopify
(links)      (links +      (full           (full
             products)     orders)         e-com)
```

---

## Problem & Solution

### Problems

| User | Problem |
|------|---------|
| Facebook Sellers | Bio te multiple link share korte pare na |
| Instagram Sellers | Product showcase er jonno website nai |
| Freelancers | Portfolio + contact ek jaygay nai |
| Small Business | Professional online presence costly |
| Everyone | bKash/Nagad info share korte gele messy |

### Solution

**BioShop** — One page with:

- All social/contact links
- Product showcase
- Payment information
- WhatsApp order button
- Professional appearance

**Setup time:** 2 minutes
**Cost:** Free to ৳299/month

---

## Target Market

### Primary Users

| Segment | Estimated Size (BD) |
|---------|---------------------|
| Facebook Page Sellers | 500,000+ |
| Instagram Sellers | 100,000+ |
| Freelancers | 200,000+ |
| Small Businesses | 300,000+ |
| Content Creators | 50,000+ |
| **Total Addressable** | **1,000,000+** |

### User Personas

#### Persona 1: Fatima (Facebook Seller)

- **Age:** 25
- **Business:** Hijab & clothing via Facebook page
- **Problem:** Customers can't easily see all products
- **Current solution:** Facebook albums (messy)
- **Budget:** ৳200-500/month
- **Goal:** Professional product showcase

#### Persona 2: Rakib (Freelancer)

- **Age:** 28
- **Work:** Graphic designer on Fiverr
- **Problem:** No portfolio website
- **Current solution:** Google Drive links
- **Budget:** ৳100-300/month
- **Goal:** Professional portfolio page

#### Persona 3: Tasnim (Influencer)

- **Age:** 22
- **Platform:** TikTok, YouTube
- **Problem:** Can't share all links in bio
- **Current solution:** Linktree (English, expensive)
- **Budget:** ৳100-200/month
- **Goal:** All links in one place

---

## Features

### MVP Features (Version 1.0)

#### Authentication

- [ ] Phone number + OTP signup
- [ ] Email signup (optional)
- [ ] Google OAuth login
- [ ] Password reset
- [ ] Session management

#### Profile Management

- [ ] Username selection (unique)
- [ ] Display name
- [ ] Profile photo upload
- [ ] Bio/description
- [ ] WhatsApp number
- [ ] Theme color picker
- [ ] Cover photo (Pro)

#### Link Management

- [ ] Add link (title + URL)
- [ ] Edit/delete links
- [ ] Drag-drop reorder
- [ ] Link icons (auto-detect or manual)
- [ ] Enable/disable links
- [ ] Link click tracking

#### Product Management

- [ ] Add product (name, price, description)
- [ ] Multiple product images
- [ ] Product categories
- [ ] Stock status (available/stock out/pre-order)
- [ ] Compare price (for discount display)
- [ ] Drag-drop reorder
- [ ] Enable/disable products

#### Payment Information

- [ ] bKash number
- [ ] Nagad number
- [ ] Rocket number
- [ ] Bank account details
- [ ] Payment QR code upload
- [ ] Payment instructions

#### Public Page

- [ ] Clean, responsive design
- [ ] Profile header section
- [ ] Links section
- [ ] Products grid section
- [ ] Product detail modal
- [ ] Payment info section
- [ ] WhatsApp order button
- [ ] Social share buttons
- [ ] Page view counter

#### Analytics (Basic)

- [ ] Total page views
- [ ] Link clicks
- [ ] Product views
- [ ] WhatsApp button clicks
- [ ] Daily/weekly/monthly stats

#### Billing & Subscription

- [ ] Free plan limits
- [ ] Plan upgrade/downgrade
- [ ] bKash payment
- [ ] Nagad payment
- [ ] Subscription management
- [ ] Invoice history

#### Settings

- [ ] Section visibility toggles
- [ ] Section order (drag-drop)
- [ ] SEO settings (title, description)
- [ ] Custom domain (Pro)
- [ ] Delete account

---

## User Flows

### Flow 1: Registration

```
Landing Page
    ↓
"Start Free" button
    ↓
Enter phone number
    ↓
Receive OTP
    ↓
Enter OTP
    ↓
Choose username
    ↓
Setup profile (name, photo)
    ↓
Dashboard
```

### Flow 2: Adding First Link

```
Dashboard
    ↓
"Add Link" button
    ↓
Enter title + URL
    ↓
Select icon (auto-suggested)
    ↓
Save
    ↓
Preview page
```

### Flow 3: Adding Product

```
Dashboard → Products
    ↓
"Add Product" button
    ↓
Enter name, price
    ↓
Upload photos
    ↓
Select category
    ↓
Set stock status
    ↓
Save
    ↓
Preview page
```

### Flow 4: Customer Order

```
Visit bioshop.io/username
    ↓
Browse products
    ↓
Click product
    ↓
View details in modal
    ↓
Click "Order via WhatsApp"
    ↓
WhatsApp opens with message:
"Hi, I want to order:
Product: Black Hijab
Price: ৳450
From: bioshop.io/hijabhouse"
```

### Flow 5: Upgrade to Pro

```
Dashboard → Billing
    ↓
View plans
    ↓
Select Pro plan
    ↓
Choose payment method (bKash/Nagad)
    ↓
Complete payment
    ↓
Plan activated
    ↓
Pro features unlocked
```

---

## Database Schema

### Entity Relationship Diagram

```
users
  │
  ├── profiles (1:1)
  │     │
  │     ├── links (1:N)
  │     │     └── link_clicks (1:N)
  │     │
  │     ├── categories (1:N)
  │     │     └── products (1:N)
  │     │           └── product_images (1:N)
  │     │
  │     ├── payment_methods (1:N)
  │     │
  │     └── page_views (1:N)
  │
  └── subscriptions (1:N)
        └── payments (1:N)
```

### Tables

#### users

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    phone VARCHAR(20) UNIQUE,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### profiles

```sql
CREATE TABLE profiles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    bio TEXT,
    photo VARCHAR(500),
    cover_photo VARCHAR(500),
    whatsapp VARCHAR(20),
    theme_color VARCHAR(7) DEFAULT '#000000',

    -- Section visibility
    show_links BOOLEAN DEFAULT TRUE,
    show_products BOOLEAN DEFAULT TRUE,
    show_payment BOOLEAN DEFAULT TRUE,

    -- Section order (JSON array)
    section_order JSON DEFAULT '["links", "products", "payment"]',

    -- SEO
    seo_title VARCHAR(60),
    seo_description VARCHAR(160),

    -- Custom domain
    custom_domain VARCHAR(255),

    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### links

```sql
CREATE TABLE links (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(500) NOT NULL,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE
);
```

#### categories

```sql
CREATE TABLE categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE
);
```

#### products

```sql
CREATE TABLE products (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    category_id BIGINT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    compare_price DECIMAL(10, 2),
    status ENUM('available', 'stock_out', 'pre_order') DEFAULT 'available',
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

#### product_images

```sql
CREATE TABLE product_images (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT NOT NULL,
    url VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

#### payment_methods

```sql
CREATE TABLE payment_methods (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    type ENUM('bkash', 'nagad', 'rocket', 'bank') NOT NULL,
    account_number VARCHAR(50),
    account_name VARCHAR(100),
    bank_name VARCHAR(100),
    branch_name VARCHAR(100),
    qr_code VARCHAR(500),
    instructions TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE
);
```

#### page_views

```sql
CREATE TABLE page_views (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    ip VARCHAR(45),
    user_agent VARCHAR(500),
    referrer VARCHAR(500),
    country VARCHAR(50),
    device VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    INDEX idx_profile_date (profile_id, created_at)
);
```

#### link_clicks

```sql
CREATE TABLE link_clicks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    link_id BIGINT NOT NULL,
    ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE,
    INDEX idx_link_date (link_id, created_at)
);
```

#### product_views

```sql
CREATE TABLE product_views (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT NOT NULL,
    ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_date (product_id, created_at)
);
```

#### whatsapp_clicks

```sql
CREATE TABLE whatsapp_clicks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_id BIGINT NOT NULL,
    product_id BIGINT,
    ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_profile_date (profile_id, created_at)
);
```

#### plans

```sql
CREATE TABLE plans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    billing_cycle ENUM('monthly', 'yearly') DEFAULT 'monthly',
    max_links INT,
    max_products INT,
    features JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### subscriptions

```sql
CREATE TABLE subscriptions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    plan_id BIGINT NOT NULL,
    status ENUM('active', 'cancelled', 'expired', 'past_due') DEFAULT 'active',
    starts_at TIMESTAMP NOT NULL,
    ends_at TIMESTAMP NOT NULL,
    cancelled_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);
```

#### payments

```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    subscription_id BIGINT,
    amount DECIMAL(10, 2) NOT NULL,
    method ENUM('bkash', 'nagad', 'card') NOT NULL,
    transaction_id VARCHAR(100),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);
```

#### otp_codes

```sql
CREATE TABLE otp_codes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    phone VARCHAR(20) NOT NULL,
    code VARCHAR(6) NOT NULL,
    purpose ENUM('registration', 'login', 'reset') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    verified_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_phone_purpose (phone, purpose)
);
```

---

## Tech Stack

### Frontend

| Technology | Purpose |
|------------|---------|
| Nuxt 3 | Vue framework with SSR |
| Vue 3 | Reactive UI |
| TypeScript | Type safety |
| Tailwind CSS | Styling |
| Shadcn-vue | UI components |
| Pinia | State management |
| VueUse | Utility composables |

### Backend

| Technology | Purpose |
|------------|---------|
| Laravel 11 | PHP framework |
| MySQL 8 | Database |
| Redis | Cache, sessions, queues |
| Laravel Sanctum | API authentication |
| Laravel Horizon | Queue monitoring |

### Infrastructure

| Service | Purpose |
|---------|---------|
| VPS (Hetzner/DO) | Hosting |
| Cloudflare | CDN, DNS, SSL |
| Cloudflare R2 | Image storage |
| BulkSMSBD | OTP SMS |
| bKash API | Payment |
| Nagad API | Payment |

### Development Tools

| Tool | Purpose |
|------|---------|
| Git | Version control |
| GitHub | Repository |
| Docker | Local development |
| GitHub Actions | CI/CD |
| Sentry | Error tracking |
| Plausible | Analytics |

---

## API Endpoints

### Authentication

```
POST   /api/auth/send-otp        Send OTP to phone
POST   /api/auth/verify-otp      Verify OTP and login/register
POST   /api/auth/login           Email/password login
POST   /api/auth/register        Email registration
POST   /api/auth/logout          Logout
POST   /api/auth/forgot-password Send password reset email
POST   /api/auth/reset-password  Reset password
GET    /api/auth/user            Get current user
```

### Profile

```
GET    /api/profile              Get current user's profile
POST   /api/profile              Create profile
PUT    /api/profile              Update profile
POST   /api/profile/photo        Upload profile photo
POST   /api/profile/cover        Upload cover photo
DELETE /api/profile/photo        Remove profile photo
GET    /api/profile/check-username/:username  Check username availability
```

### Links

```
GET    /api/links                List all links
POST   /api/links                Create link
GET    /api/links/:id            Get link
PUT    /api/links/:id            Update link
DELETE /api/links/:id            Delete link
PUT    /api/links/reorder        Reorder links
```

### Categories

```
GET    /api/categories           List all categories
POST   /api/categories           Create category
PUT    /api/categories/:id       Update category
DELETE /api/categories/:id       Delete category
PUT    /api/categories/reorder   Reorder categories
```

### Products

```
GET    /api/products             List all products
POST   /api/products             Create product
GET    /api/products/:id         Get product
PUT    /api/products/:id         Update product
DELETE /api/products/:id         Delete product
PUT    /api/products/reorder     Reorder products
POST   /api/products/:id/images  Upload product images
DELETE /api/products/:id/images/:imageId  Delete product image
```

### Payment Methods

```
GET    /api/payment-methods              List payment methods
POST   /api/payment-methods              Create payment method
PUT    /api/payment-methods/:id          Update payment method
DELETE /api/payment-methods/:id          Delete payment method
POST   /api/payment-methods/:id/qr       Upload QR code
```

### Analytics

```
GET    /api/analytics/overview           Get overview stats
GET    /api/analytics/page-views         Get page view stats
GET    /api/analytics/link-clicks        Get link click stats
GET    /api/analytics/product-views      Get product view stats
GET    /api/analytics/whatsapp-clicks    Get WhatsApp click stats
```

### Billing

```
GET    /api/billing/plans                List available plans
GET    /api/billing/subscription         Get current subscription
POST   /api/billing/subscribe            Subscribe to plan
POST   /api/billing/cancel               Cancel subscription
GET    /api/billing/payments             List payment history
POST   /api/billing/payment/bkash        Initiate bKash payment
POST   /api/billing/payment/nagad        Initiate Nagad payment
POST   /api/billing/webhook/bkash        bKash payment webhook
POST   /api/billing/webhook/nagad        Nagad payment webhook
```

### Public (No Auth)

```
GET    /api/public/:username             Get public page data
POST   /api/public/:username/view        Track page view
POST   /api/public/links/:id/click       Track link click
POST   /api/public/products/:id/view     Track product view
POST   /api/public/whatsapp-click        Track WhatsApp click
```

---

## Page Structure

### Public Pages

```
/                           Landing page
/pricing                    Pricing page
/features                   Features page
/about                      About page
/contact                    Contact page
/privacy                    Privacy policy
/terms                      Terms of service
/:username                  User's public bio page
```

### Auth Pages

```
/login                      Login page
/register                   Registration page
/forgot-password            Forgot password page
/reset-password             Reset password page
```

### Dashboard Pages

```
/dashboard                  Dashboard home (overview)
/dashboard/links            Manage links
/dashboard/products         Manage products
/dashboard/products/new     Add new product
/dashboard/products/:id     Edit product
/dashboard/categories       Manage categories
/dashboard/payment          Manage payment methods
/dashboard/analytics        View analytics
/dashboard/settings         General settings
/dashboard/settings/profile Profile settings
/dashboard/settings/seo     SEO settings
/dashboard/settings/domain  Custom domain settings
/dashboard/billing          Billing & subscription
/dashboard/billing/upgrade  Upgrade plan
```

---

## UI Components

### Core Components

```
components/
├── common/
│   ├── Logo.vue
│   ├── Button.vue
│   ├── Input.vue
│   ├── Modal.vue
│   ├── Dropdown.vue
│   ├── Avatar.vue
│   ├── Badge.vue
│   ├── Card.vue
│   ├── Spinner.vue
│   ├── Toast.vue
│   └── EmptyState.vue
│
├── layout/
│   ├── Navbar.vue
│   ├── Sidebar.vue
│   ├── Footer.vue
│   ├── DashboardLayout.vue
│   └── PublicLayout.vue
│
├── auth/
│   ├── OtpInput.vue
│   ├── PhoneInput.vue
│   └── SocialButtons.vue
│
├── dashboard/
│   ├── StatsCard.vue
│   ├── QuickActions.vue
│   ├── RecentActivity.vue
│   └── PreviewButton.vue
│
├── links/
│   ├── LinkCard.vue
│   ├── LinkForm.vue
│   ├── LinkList.vue
│   └── IconPicker.vue
│
├── products/
│   ├── ProductCard.vue
│   ├── ProductForm.vue
│   ├── ProductGrid.vue
│   ├── ProductImageUpload.vue
│   ├── CategorySelect.vue
│   └── StockBadge.vue
│
├── payment/
│   ├── PaymentMethodCard.vue
│   ├── PaymentMethodForm.vue
│   └── QrUpload.vue
│
├── analytics/
│   ├── Chart.vue
│   ├── StatCard.vue
│   └── TopItems.vue
│
├── billing/
│   ├── PlanCard.vue
│   ├── CurrentPlan.vue
│   └── PaymentHistory.vue
│
└── public/
    ├── ProfileHeader.vue
    ├── LinksSection.vue
    ├── ProductsSection.vue
    ├── ProductModal.vue
    ├── PaymentSection.vue
    ├── WhatsAppButton.vue
    └── ShareButtons.vue
```

---

## Pricing Model

### Plans

| Feature | Free | Starter | Pro | Business |
|---------|------|---------|-----|----------|
| **Price** | ৳0 | ৳149/mo | ৳299/mo | ৳499/mo |
| Links | 5 | 20 | Unlimited | Unlimited |
| Products | 5 | 30 | Unlimited | Unlimited |
| BioShop Branding | Yes | No | No | No |
| Analytics | Basic | Full | Full | Full |
| Custom Domain | No | No | Yes | Yes |
| Multiple Pages | No | No | No | Yes |
| Team Members | No | No | No | 3 |
| Priority Support | No | No | Yes | Yes |
| Custom CSS | No | No | Yes | Yes |

### Yearly Discount

- **Starter:** ৳149 × 12 = ৳1,788 → ৳1,490/year (17% off)
- **Pro:** ৳299 × 12 = ৳3,588 → ৳2,990/year (17% off)
- **Business:** ৳499 × 12 = ৳5,988 → ৳4,990/year (17% off)

---

## Development Roadmap

### Phase 1: Foundation (Week 1)

```
Day 1:
  □ Project setup (Nuxt 3 + Laravel 11)
  □ Docker configuration
  □ Database migrations
  □ CI/CD pipeline setup

Day 2:
  □ Auth: Phone OTP flow
  □ Auth: Email registration
  □ Auth: Login/logout

Day 3:
  □ Profile: Create/update
  □ Profile: Photo upload
  □ Username validation

Day 4:
  □ Links: CRUD operations
  □ Links: Drag-drop reorder
  □ Links: Icon detection

Day 5:
  □ Public page: Basic layout
  □ Public page: Links section
  □ Page view tracking

Day 6-7:
  □ Testing & bug fixes
  □ Code review
  □ Documentation
```

### Phase 2: Products (Week 2)

```
Day 8:
  □ Categories: CRUD
  □ Products: Basic CRUD

Day 9:
  □ Products: Image upload
  □ Products: Multiple images
  □ Image optimization

Day 10:
  □ Products: Stock status
  □ Products: Reorder
  □ Public page: Products section

Day 11:
  □ Product modal (public)
  □ WhatsApp order button
  □ Click tracking

Day 12:
  □ Payment methods: CRUD
  □ QR code upload
  □ Public page: Payment section

Day 13-14:
  □ Testing
  □ Mobile responsive
  □ Performance optimization
```

### Phase 3: Analytics & Billing (Week 3)

```
Day 15:
  □ Analytics: Dashboard overview
  □ Analytics: Page views chart
  □ Analytics: Link clicks

Day 16:
  □ Analytics: Product views
  □ Analytics: Top items
  □ Analytics: Date filters

Day 17:
  □ Plans: Database seed
  □ Subscription: Model & logic
  □ Plan limits enforcement

Day 18:
  □ bKash: Payment integration
  □ Nagad: Payment integration
  □ Payment webhooks

Day 19:
  □ Billing dashboard
  □ Plan upgrade flow
  □ Payment history

Day 20-21:
  □ Testing payment flows
  □ Error handling
  □ Edge cases
```

### Phase 4: Polish & Launch (Week 4)

```
Day 22:
  □ Landing page
  □ Pricing page
  □ Features page

Day 23:
  □ Theme customization
  □ Section reorder
  □ SEO settings

Day 24:
  □ Custom domain setup
  □ SSL configuration
  □ DNS instructions

Day 25:
  □ Help/FAQ page
  □ Contact form
  □ Terms & Privacy

Day 26:
  □ Final testing
  □ Security audit
  □ Performance audit

Day 27:
  □ Production deployment
  □ Monitoring setup
  □ Error tracking

Day 28:
  □ Beta user invites
  □ Feedback collection
  □ Launch!
```

---

## Marketing Strategy

### Pre-Launch (Week 1-2)

1. **Facebook Groups Join:**
   - Online Business Bangladesh
   - Facebook Sellers BD
   - Women Entrepreneurs BD
   - Freelancers BD
   - Clothing/Hijab seller groups

2. **Content Seeding:**
   - Posts about problems (not selling)
   - "How do you share product links?"
   - Engage in discussions

3. **Personal Outreach:**
   - DM 20-30 potential users
   - Ask about their problems
   - Build relationships

### Beta Launch (Week 3)

1. **Free Beta Access:**
   - "First 100 users FREE lifetime Starter"
   - Create urgency

2. **Group Posts:**
   - Problem → Solution format
   - Before/after screenshots
   - Simple signup link

3. **Target:** 50-100 beta users

### Public Launch (Week 4+)

1. **Success Stories:**
   - Screenshot testimonials
   - "Sales increased X%"
   - Real user profiles

2. **Referral Program:**
   - Refer 3 friends = 1 month free
   - Viral loop

3. **Content Marketing:**
   - "Facebook e kivabe sell baraben"
   - "Product photography tips"
   - "WhatsApp marketing guide"

4. **Influencer Collaboration:**
   - Free Pro accounts
   - Review/mention requests

### Ongoing

1. **SEO:**
   - Blog posts
   - User page indexing
   - Local BD keywords

2. **Paid Ads (Later):**
   - Facebook/Instagram
   - Target: Facebook page admins

3. **Partnerships:**
   - Freelancer communities
   - Business associations
   - Delivery services

---

## Revenue Projections

### Year 1

| Month | Users | Paid (15%) | Avg ৳ | MRR |
|-------|-------|------------|-------|-----|
| 1 | 100 | 0 | - | ৳0 |
| 2 | 200 | 15 | 180 | ৳2,700 |
| 3 | 350 | 40 | 190 | ৳7,600 |
| 4 | 500 | 70 | 200 | ৳14,000 |
| 5 | 700 | 100 | 210 | ৳21,000 |
| 6 | 900 | 130 | 220 | ৳28,600 |
| 7 | 1,100 | 165 | 230 | ৳37,950 |
| 8 | 1,400 | 210 | 240 | ৳50,400 |
| 9 | 1,700 | 255 | 245 | ৳62,475 |
| 10 | 2,000 | 300 | 250 | ৳75,000 |
| 11 | 2,400 | 360 | 255 | ৳91,800 |
| 12 | 2,800 | 420 | 260 | ৳109,200 |

**Year 1 Total Revenue:** ~৳500,000

### Year 2

| Quarter | Users | Paid | MRR |
|---------|-------|------|-----|
| Q1 | 4,000 | 600 | ৳165,000 |
| Q2 | 6,000 | 900 | ৳250,000 |
| Q3 | 8,500 | 1,275 | ৳350,000 |
| Q4 | 12,000 | 1,800 | ৳500,000 |

**Year 2 Total Revenue:** ~৳3,000,000+

---

## KPIs & Metrics

### Growth Metrics

| Metric | Target |
|--------|--------|
| Signups/week | 50+ |
| Active users (WAU) | 60%+ |
| Free → Paid conversion | 15-20% |
| Monthly churn | <5% |

### Product Metrics

| Metric | Target |
|--------|--------|
| Avg links per user | 5+ |
| Avg products per user | 8+ |
| Pages with products | 60%+ |
| Public page visits/user | 100+/month |

### Revenue Metrics

| Metric | Target |
|--------|--------|
| ARPU (paid) | ৳250+ |
| LTV | ৳3,000+ |
| CAC | <৳150 |
| LTV:CAC ratio | 20:1+ |

### Technical Metrics

| Metric | Target |
|--------|--------|
| Page load time | <2s |
| API response time | <200ms |
| Uptime | 99.9%+ |
| Error rate | <0.1% |

---

## Future Features

### Version 2.0 (Month 3-4)

- [ ] Order management (basic)
- [ ] Customer list
- [ ] Inventory tracking
- [ ] Discount codes
- [ ] Multiple themes
- [ ] Email notifications
- [ ] Scheduled links

### Version 3.0 (Month 5-6)

- [ ] Direct checkout
- [ ] bKash/Nagad auto payment
- [ ] Delivery integration (Pathao, RedX)
- [ ] Invoice generation
- [ ] Multi-user/team
- [ ] API for developers

### Version 4.0 (Month 7+)

- [ ] Mobile app
- [ ] WhatsApp Business integration
- [ ] Facebook Shop sync
- [ ] AI product descriptions
- [ ] Bulk import/export
- [ ] Advanced analytics
- [ ] Affiliate system

---

## Costs & Investment

### Initial Setup

| Item | Cost |
|------|------|
| Domain (bioshop.io) | ৳3,000/year |
| Logo design | ৳5,000 |
| **Total** | ৳8,000 |

### Monthly Costs

| Item | Cost |
|------|------|
| VPS (4GB) | ৳2,000/month |
| Cloudflare R2 | ৳500/month |
| SMS (OTP) | ৳2,000/month |
| Email service | ৳500/month |
| Monitoring | ৳500/month |
| **Total** | ৳5,500/month |

### Year 1 Total

| Period | Cost |
|--------|------|
| Initial | ৳8,000 |
| Monthly × 12 | ৳66,000 |
| Marketing | ৳20,000 |
| Contingency | ৳10,000 |
| **Total** | ৳104,000 |

### Break-Even

- Monthly cost: ৳5,500
- Avg revenue/paid user: ৳230
- Break-even: **24 paid users**

---

## Risks & Mitigation

### Technical Risks

| Risk | Mitigation |
|------|------------|
| Downtime | Redundant hosting, monitoring |
| Data loss | Daily backups, replication |
| Security breach | Security audits, encryption |
| Scaling issues | Cloud infrastructure, caching |

### Business Risks

| Risk | Mitigation |
|------|------------|
| Low adoption | Strong free tier, viral features |
| High churn | Continuous value, low price |
| Competition | Niche focus, local features |
| Payment failures | Multiple payment methods |

### Market Risks

| Risk | Mitigation |
|------|------------|
| Economic downturn | Low price point, essential tool |
| Platform changes | Diversify integrations |
| Regulation | Compliance, legal review |

---

## Team (Future)

### Phase 1 (Solo)

- Full-stack development
- Marketing
- Customer support

### Phase 2 (2-3 people)

- Developer (frontend)
- Marketing/sales
- Customer support

### Phase 3 (5+ people)

- Backend developer
- Frontend developer
- Designer
- Marketing
- Customer success
- Finance

---

## Contact & Links

- **Website:** bioshop.io (planned)
- **Email:** support@bioshop.io (planned)
- **GitHub:** github.com/[username]/bioshop (private)

---

## Changelog

| Version | Date | Changes |
|---------|------|---------|
| 0.1 | YYYY-MM-DD | Initial documentation |

---

## Appendix

### A. Competitor Analysis

| Platform | Strengths | Weaknesses |
|----------|-----------|------------|
| Linktree | Brand, features | No products, expensive, no BD payment |
| Beacons | Good design | Complex, English only |
| Bio.link | Free | Limited features |
| Local F-commerce | Orders | Complex, expensive |

### B. Sample WhatsApp Message

```
Hi, I want to order:

Product: Premium Black Hijab
Price: ৳450
Quantity: 1

From: bioshop.io/hijabhouse

Please confirm availability.
```

### C. SEO Keywords

- link in bio bangladesh
- facebook seller link
- product showcase link
- bioshop bd
- online shop link
- bkash payment link
- bangla link in bio

---

*Document created for BioShop project planning and development.*
