import { test, expect } from '@playwright/test';

test.describe('BioShop Public Pages', () => {

    test('Homepage loads correctly', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/homepage.png', fullPage: true });
    });

    test('Features page loads correctly', async ({ page }) => {
        await page.goto('/features');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/features.png', fullPage: true });
    });

    test('Pricing page loads correctly', async ({ page }) => {
        await page.goto('/pricing');
        await expect(page).toHaveTitle(/BioShop/);
        // Check for pricing plans
        const content = await page.content();
        expect(content).toContain('Free');
        expect(content).toContain('Starter');
        expect(content).toContain('Pro');
        await page.screenshot({ path: 'test-results/pricing.png', fullPage: true });
    });

    test('About page loads correctly', async ({ page }) => {
        await page.goto('/about');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/about.png', fullPage: true });
    });

    test('Contact page loads correctly', async ({ page }) => {
        await page.goto('/contact');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/contact.png', fullPage: true });
    });

    test('Login page loads correctly', async ({ page }) => {
        await page.goto('/login');
        await expect(page).toHaveTitle(/BioShop/);
        // Check for login form elements
        await expect(page.locator('input[type="email"], input[name="email"]')).toBeVisible();
        await expect(page.locator('input[type="password"]')).toBeVisible();
        await page.screenshot({ path: 'test-results/login.png', fullPage: true });
    });

    test('Register page loads correctly', async ({ page }) => {
        await page.goto('/register');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/register.png', fullPage: true });
    });
});

test.describe('BioShop Public Shops', () => {

    test('Hijab House shop loads correctly', async ({ page }) => {
        await page.goto('/hijabhouse');
        await expect(page).toHaveTitle(/BioShop/);
        const content = await page.content();
        expect(content).toContain('Fatima Rahman');
        await page.screenshot({ path: 'test-results/shop-hijabhouse.png', fullPage: true });
    });

    test('Tech Gadgets shop loads correctly', async ({ page }) => {
        await page.goto('/techgadgetsbd');
        await expect(page).toHaveTitle(/BioShop/);
        const content = await page.content();
        expect(content).toContain('Rakib Hassan');
        await page.screenshot({ path: 'test-results/shop-techgadgets.png', fullPage: true });
    });
});

test.describe('Authentication Flow', () => {

    test('Seller can login', async ({ page }) => {
        await page.goto('/login');
        await page.fill('input[type="email"], input[name="email"]', 'fatima@bioshop.com');
        await page.fill('input[type="password"]', 'password123');
        await page.screenshot({ path: 'test-results/login-filled.png' });
        await page.click('button[type="submit"]');
        await page.waitForURL(/dashboard|setup/, { timeout: 10000 });
        await page.screenshot({ path: 'test-results/seller-dashboard.png', fullPage: true });
    });

    test('Admin can login', async ({ page }) => {
        await page.goto('/login');
        await page.fill('input[type="email"], input[name="email"]', 'admin@bioshop.com');
        await page.fill('input[type="password"]', 'password123');
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        await page.goto('/admin');
        await page.waitForURL(/admin/, { timeout: 10000 });
        await page.screenshot({ path: 'test-results/admin-dashboard.png', fullPage: true });
    });
});
