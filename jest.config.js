/**
 * Jest Configuration
 *
 * Sets up a simulated DOM environment for React components.
 */
export default {
  testEnvironment: "jsdom",
  setupFilesAfterEnv: ["@testing-library/jest-dom"],
  moduleNameMapper: {
    "\\.(css|less|scss|sass)$": "<rootDir>/tests/js/__mocks__/styleMock.js",
  },
  transform: {
    "^.+\\.[t|j]sx?$": "babel-jest",
  },
};
