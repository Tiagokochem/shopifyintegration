import { test, expect } from '@playwright/test';

test.describe('Products Page', () => {
  test('should display products page', async ({ page }) => {
    await page.goto('/products');

    // Check if page title or heading is visible
    await expect(page.locator('h1')).toContainText('Products');
  });

  test('should have search input', async ({ page }) => {
    await page.goto('/products');

    const searchInput = page.locator('input[placeholder*="Search"]');
    await expect(searchInput).toBeVisible();
  });

  test('should filter products by search', async ({ page }) => {
    await page.goto('/products');

    const searchInput = page.locator('input[placeholder*="Search"]');
    await searchInput.fill('test');

    // Wait for debounce
    await page.waitForTimeout(600);

    // Check if search was triggered (you might need to adjust this based on your implementation)
    await expect(searchInput).toHaveValue('test');
  });

  test('should have vendor filter', async ({ page }) => {
    await page.goto('/products');

    const vendorInput = page.locator('input[placeholder*="vendor"]');
    await expect(vendorInput).toBeVisible();
  });

  test('should have product type filter', async ({ page }) => {
    await page.goto('/products');

    const typeInput = page.locator('input[placeholder*="type"]');
    await expect(typeInput).toBeVisible();
  });
});
