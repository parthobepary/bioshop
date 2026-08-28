import { test, expect } from '@playwright/test';

test.use({
    launchOptions: { slowMo: 800 },
    viewport: { width: 1280, height: 800 }
});

test('Complete user journey test', async ({ page }) => {
    test.setTimeout(120000);

    // 1. Homepage
    await page.goto('/');
    await page.waitForTimeout(2000);

    // 2. Features
    await page.click('a:has-text("Features")');
    await page.waitForTimeout(2000);

    // 3. Pricing
    await page.click('a:has-text("Pricing")');
    await page.waitForTimeout(2000);

    // 4. About
    await page.click('a:has-text("About")');
    await page.waitForTimeout(2000);

    // 5. Contact
    await page.click('a:has-text("Contact")');
    await page.waitForTimeout(2000);

    // 6. Shop page
    await page.goto('/hijabhouse');
    await page.waitForTimeout(2500);

    // 7. Login page
    await page.goto('/login');
    await page.waitForTimeout(1500);

    // 8. Fill & submit login
    await page.locator('input[id="email"]').fill('fatima@bioshop.com');
    await page.waitForTimeout(500);
    await page.locator('input[id="password"]').fill('password123');
    await page.waitForTimeout(800);
    await page.locator('button[type="submit"]').click();
    await page.waitForURL(/dashboard|setup/, { timeout: 15000 });
    await page.waitForTimeout(2000);

    // 9. Dashboard navigation
    const links = page.locator('a:has-text("Links")').first();
    if (await links.isVisible()) {
        await links.click();
        await page.waitForTimeout(2000);
    }

    const products = page.locator('a:has-text("Products")').first();
    if (await products.isVisible()) {
        await products.click();
        await page.waitForTimeout(2000);
    }

    const analytics = page.locator('a:has-text("Analytics")').first();
    if (await analytics.isVisible()) {
        await analytics.click();
        await page.waitForTimeout(2000);
    }

    // Done
    await page.waitForTimeout(2000);
});
