import { test, expect } from '@playwright/test';

test.describe('BioShop Public Pages', () => {

    test('Homepage loads correctly', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveTitle(/BioShop/);
        await page.screenshot({ path: 'test-results/homepage.png', fullPage: true });
    });

    test('Marketing sections all live on the home page', async ({ page }) => {
        await page.goto('/');

        for (const id of ['features', 'how', 'pricing', 'reviews', 'about', 'faq', 'contact']) {
            await expect(page.locator(`section#${id}`)).toHaveCount(1);
        }

        // Plans are rendered inside the pricing section
        const content = await page.content();
        expect(content).toContain('Free');
        expect(content).toContain('Starter');
        expect(content).toContain('Pro');
    });

    test('Legacy marketing URLs redirect to their home anchors', async ({ page }) => {
        for (const [path, anchor] of [
            ['/features', '#features'],
            ['/pricing', '#pricing'],
            ['/about', '#about'],
            ['/contact', '#contact'],
        ]) {
            await page.goto(path);
            expect(new URL(page.url()).pathname).toBe('/');
            expect(page.url()).toContain(anchor);
        }
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
