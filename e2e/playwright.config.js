// @ts-check
const { defineConfig, devices } = require("@playwright/test");

const isCI = !!process.env.CI;

module.exports = defineConfig({
  testDir: "./tests",
  fullyParallel: true,
  forbidOnly: isCI,
  retries: isCI ? 1 : 0,
  workers: isCI ? 4 : undefined,
  timeout: 120_000,
  expect: {
    timeout: 10_000,
  },
  reporter: "html",
  use: {
    ignoreHTTPSErrors: true,
    trace: isCI ? "off" : "on-first-retry",
    navigationTimeout: 30_000,
    actionTimeout: 10_000,
  },

  projects: isCI
    ? [
        {
          name: "chromium",
          use: { ...devices["Desktop Chrome"] },
        },
      ]
    : [
        {
          name: "chromium",
          use: { ...devices["Desktop Chrome"] },
        },
        {
          name: "firefox",
          use: { ...devices["Desktop Firefox"] },
        },
        {
          name: "webkit",
          use: { ...devices["Desktop Safari"] },
        },
      ],
});
