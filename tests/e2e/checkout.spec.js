import { test, expect } from "@playwright/test";

/**
 * WooCommerce Checkout Flow Smoke Test
 *
 * Automates a user journey: visiting the shop, adding an item to the cart,
 * and ensuring the checkout page loads the correct WooCommerce blocks.
 */
test("User can successfully add product to cart and view checkout", async ({
  page,
}) => {
  // 1. Navigate to the shop archive
  await page.goto("/shop/");

  // 2. Find the first 'Add to cart' button and click it
  const addToCartBtn = page.locator(".add_to_cart_button").first();
  await addToCartBtn.click();

  // 3. Wait for the AJAX cart fragment to update
  const viewCartLink = page.locator("a.added_to_cart");
  await expect(viewCartLink).toBeVisible({ timeout: 5000 });

  // 4. Navigate directly to checkout
  await page.goto("/checkout/");

  // 5. Verify the WooCommerce Checkout block rendered successfully
  const checkoutForm = page.locator(".wc-block-checkout");
  await expect(checkoutForm).toBeVisible();
});
