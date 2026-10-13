/**
 * Node.js script to create placeholder PWA icons
 * Run with: node create-placeholder-icons.js
 * 
 * This creates simple colored square placeholders for PWA icons
 * Replace with actual NIESV branded icons later
 */

const fs = require('fs');
const path = require('path');

// Icon sizes needed for PWA
const iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

// NIESV brand colors
const primaryColor = '#000028';
const secondaryColor = '#49739c';
const accentColor = '#d4af37';
const backgroundColor = '#f8fafc';

/**
 * Create a simple SVG icon placeholder
 */
function createSVGIcon(size) {
    return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:${primaryColor};stop-opacity:1" />
      <stop offset="100%" style="stop-color:${secondaryColor};stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Background -->
  <rect width="${size}" height="${size}" fill="url(#gradient)" rx="${size * 0.125}"/>
  
  <!-- NIESV Icon -->
  <g transform="translate(${size * 0.25}, ${size * 0.25})">
    <!-- Building representation -->
    <rect x="0" y="${size * 0.3125}" width="${size * 0.5}" height="${size * 0.1875}" fill="${backgroundColor}" rx="${size * 0.015625}"/>
    <rect x="${size * 0.0625}" y="${size * 0.25}" width="${size * 0.375}" height="${size * 0.25}" fill="#e7edf4" rx="${size * 0.015625}"/>
    <rect x="${size * 0.125}" y="${size * 0.1875}" width="${size * 0.25}" height="${size * 0.3125}" fill="#ffffff" rx="${size * 0.015625}"/>
    
    <!-- Windows -->
    <rect x="${size * 0.15625}" y="${size * 0.234375}" width="${size * 0.046875}" height="${size * 0.046875}" fill="${primaryColor}" rx="${size * 0.00390625}"/>
    <rect x="${size * 0.234375}" y="${size * 0.234375}" width="${size * 0.046875}" height="${size * 0.046875}" fill="${primaryColor}" rx="${size * 0.00390625}"/>
    <rect x="${size * 0.3125}" y="${size * 0.234375}" width="${size * 0.046875}" height="${size * 0.046875}" fill="${primaryColor}" rx="${size * 0.00390625}"/>
    
    <!-- Door -->
    <rect x="${size * 0.234375}" y="${size * 0.328125}" width="${size * 0.046875}" height="${size * 0.070313}" fill="${accentColor}" rx="${size * 0.00390625}"/>
  </g>
  
  <!-- NIESV Text -->
  <text x="${size * 0.5}" y="${size * 0.78125}" font-family="Arial, sans-serif" font-size="${size * 0.09375}" font-weight="bold" text-anchor="middle" fill="#ffffff">NIESV</text>
</svg>`;
}

/**
 * Create all placeholder icons
 */
function createPlaceholderIcons() {
    console.log('Creating NIESV PWA placeholder icons...');
    
    // Create icons directory if it doesn't exist
    const iconsDir = path.join(__dirname);
    if (!fs.existsSync(iconsDir)) {
        fs.mkdirSync(iconsDir, { recursive: true });
    }
    
    // Create SVG icons for each size
    iconSizes.forEach(size => {
        const svgContent = createSVGIcon(size);
        const filename = `icon-${size}x${size}.svg`;
        const filepath = path.join(iconsDir, filename);
        
        fs.writeFileSync(filepath, svgContent);
        console.log(`Created ${filename}`);
    });
    
    // Create shortcut icons (simplified versions)
    const shortcuts = [
        { name: 'news-shortcut.svg', icon: '📰', color: secondaryColor },
        { name: 'events-shortcut.svg', icon: '📅', color: accentColor },
        { name: 'properties-shortcut.svg', icon: '🏢', color: primaryColor },
        { name: 'dashboard-shortcut.svg', icon: '📊', color: secondaryColor }
    ];
    
    shortcuts.forEach(shortcut => {
        const svgContent = createShortcutIcon(96, shortcut.icon, shortcut.color);
        const filepath = path.join(iconsDir, shortcut.name);
        fs.writeFileSync(filepath, svgContent);
        console.log(`Created ${shortcut.name}`);
    });
    
    console.log('All placeholder icons created successfully!');
    console.log('Note: These are placeholder icons. Replace with actual NIESV branded icons for production.');
}

/**
 * Create shortcut icon SVG
 */
function createShortcutIcon(size, emoji, bgColor) {
    return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="${size}" height="${size}" fill="${bgColor}" rx="${size * 0.125}"/>
  
  <!-- Icon/Emoji -->
  <text x="${size * 0.5}" y="${size * 0.65}" font-family="Arial, sans-serif" font-size="${size * 0.4}" text-anchor="middle" fill="#ffffff">${emoji}</text>
</svg>`;
}

// Run the script
if (require.main === module) {
    createPlaceholderIcons();
}

module.exports = { createPlaceholderIcons, createSVGIcon };
