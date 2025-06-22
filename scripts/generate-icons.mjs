import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const inputSVG = path.resolve(__dirname, '../src/extension/assets/mensahe-logo.svg');
const outputDir = path.resolve(__dirname, '../src/extension/assets/icons');

fs.mkdirSync(outputDir, { recursive: true });
console.log(`Input SVG: ${inputSVG}`);
console.log(`Output Dir: ${outputDir}`);

const sizes = [16, 24, 32, 48, 64, 128, 256];

sizes.forEach(size => {
  const outputPNG = path.join(outputDir, `icon-${size}x${size}.png`);
  console.log(`Generating: ${outputPNG}`);
  sharp(inputSVG)
    .resize(size, size)
    .toFile(outputPNG, (err, info) => {
      if (err) {
        console.error(`Error generating ${outputPNG}:`, err);
      } else {
        console.log(`Generated: ${outputPNG}`);
      }
    });
}); 