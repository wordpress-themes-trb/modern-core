import fs from "fs";
import path from "path";
import archiver from "archiver";
import { fileURLToPath } from "url";

/**
 * Production ZIP Packager
 *
 * Generates a clean, production-ready modern-core.zip file for direct
 * upload to server environments, excluding all local dev dependencies.
 */

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootDir = path.resolve(__dirname, "..");
const outputFilePath = path.join(rootDir, "modern-core.zip");

const output = fs.createWriteStream(outputFilePath);
const archive = archiver("zip", { zlib: { level: 9 } });

output.on("close", () => {
  console.log(`\n✅ Build Complete! ${archive.pointer()} total bytes.`);
  console.log(`Ready for hPanel upload: ${outputFilePath}\n`);
});

archive.on("error", (err) => {
  throw err;
});
archive.pipe(output);

// Files and folders to strictly ignore in the production ZIP
const ignoreList = [
  "node_modules/**",
  "tests/**",
  "scripts/**",
  ".git/**",
  ".github/**",
  "assets/src/**",
  "*.zip",
  "package.json",
  "package-lock.json",
  "composer.json",
  "composer.lock",
  "phpunit.xml.dist",
  "jest.config.js",
  "playwright.config.js",
  ".eslintrc",
  ".prettierrc",
  "phpcs.xml",
];

archive.glob("**/*", {
  cwd: rootDir,
  ignore: ignoreList,
  dot: true,
});

archive.finalize();
