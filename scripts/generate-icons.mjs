/**
 * Renders public/favicon.svg to the raster sizes browsers still ask for, and
 * wraps the 32px PNG in an ICO container for /favicon.ico.
 */
import { chromium } from 'playwright';
import { readFileSync, writeFileSync } from 'node:fs';

const svg = readFileSync('public/favicon.svg', 'utf8');
const browser = await chromium.launch();

async function render(size, { bleed = false } = {}) {
    const page = await browser.newPage({ viewport: { width: size, height: size }, deviceScaleFactor: 1 });
    // `bleed` fills the corners, so Apple's own rounding does not clip the mark.
    await page.setContent(
        `<style>html,body{margin:0;padding:0;${bleed ? 'background:#5B3DE5;' : 'background:transparent;'}}
         svg{display:block;width:${size}px;height:${size}px}
         ${bleed ? 'svg rect:first-of-type{rx:0}' : ''}</style>${svg}`,
    );
    const buffer = await page.screenshot({ omitBackground: !bleed });
    await page.close();
    return buffer;
}

const png32 = await render(32);
writeFileSync('public/favicon-32.png', png32);
writeFileSync('public/favicon-192.png', await render(192));
writeFileSync('public/apple-touch-icon.png', await render(180, { bleed: true }));

// ICO: 6-byte header + one 16-byte directory entry + the PNG payload.
const header = Buffer.alloc(6);
header.writeUInt16LE(0, 0); // reserved
header.writeUInt16LE(1, 2); // type: icon
header.writeUInt16LE(1, 4); // one image

const entry = Buffer.alloc(16);
entry.writeUInt8(32, 0); // width
entry.writeUInt8(32, 1); // height
entry.writeUInt8(0, 2); // palette colours
entry.writeUInt8(0, 3); // reserved
entry.writeUInt16LE(1, 4); // colour planes
entry.writeUInt16LE(32, 6); // bits per pixel
entry.writeUInt32LE(png32.length, 8);
entry.writeUInt32LE(header.length + entry.length, 12);

writeFileSync('public/favicon.ico', Buffer.concat([header, entry, png32]));

await browser.close();
console.log('icons written');
