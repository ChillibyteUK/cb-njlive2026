// generate-theme-json.js
// Node.js script to parse SCSS tokens and generate theme.json for WordPress theme
//
// Usage:
//   npm run generate-theme-json
//
// This script reads CSS custom properties from src/sass/theme/_tokens.scss
// and generates a theme.json file with color palette and font sizes for
// the WordPress block editor.
//
// The script looks for:
// - Color variables: --col-* (excluding -hsl variants)
// - Font size variables: --fs-*
//
// Colors defined as hsl(var(--col-*-hsl)) are automatically resolved to their
// actual HSL values.

const fs = require('fs');
const path = require('path');

const scssFile = path.join(__dirname, 'src/sass/theme/_tokens.scss');
const themeJsonFile = path.join(__dirname, 'theme.json');


// Parse CSS custom properties from :root block
function parseCssVariables(scssContent) {
  const rootBlockMatch = scssContent.match(/:root\s*{([\s\S]*?)}/);
  if (!rootBlockMatch) return {};
  const rootContent = rootBlockMatch[1];
  const varRegex = /--([\w-]+):\s*([^;]+);/g;
  const tokens = {};
  let match;
  while ((match = varRegex.exec(rootContent)) !== null) {
    tokens[match[1]] = match[2].trim();
  }
  return tokens;
}

// Map CSS variables to theme.json structure

function buildThemeJson(tokens) {
  // Helper to resolve hsl(var(--col-*-hsl)) to actual hsl value
  function resolveColorValue(value, key) {
    const hslVarMatch = value.match(/^hsl\(var\(--([\w-]+-hsl)\)\)$/);
    if (hslVarMatch) {
      const hslKey = hslVarMatch[1];
      if (tokens[hslKey]) {
        return `hsl(${tokens[hslKey]})`;
      }
    }
    return value;
  }

  // Map color variables (starts with col-, but not -hsl)
  const colors = Object.entries(tokens)
    .filter(([key]) => key.startsWith('col-') && !key.endsWith('-hsl'))
    .map(([key, value]) => ({
      name: key.replace('col-', ''),
      slug: key.replace('col-', ''),
      color: resolveColorValue(value, key)
    }));

  // Map font size variables (starts with fs-)
  // WordPress sanitizes slugs and adds hyphens between letters/numbers
  // So we map h2 -> heading-2, h3 -> heading-3 etc to avoid has-h-2-font-size
  const slugMap = {
    'h2': 'heading-2', 
    'h3': 'heading-3',
    'h4': 'heading-4',
    'h5': 'heading-5',
    'h6': 'heading-6'
  };
  
  const fontSizes = Object.entries(tokens)
    .filter(([key]) => key.startsWith('fs-'))
    .map(([key, value]) => {
      const baseName = key.replace('fs-', '');
      const slug = slugMap[baseName] || baseName;
      return {
        name: baseName,
        slug: slug,
        size: value
      };
    });

  return {
    version: 2,
    settings: {
      appearanceTools: true,
      layout: {
        // Match Bootstrap container max widths so align-wide/full behave as expected
        contentSize: '1140px',
        wideSize: '1320px'
      },
      color: { 
        defaultPalette: false,
        palette: colors.map(color => ({
          ...color,
          origin: 'theme'
        }))
      },
      typography: { fontSizes: fontSizes }
    }
  };
}

function main() {
  if (!fs.existsSync(scssFile)) {
    console.error('SCSS tokens file not found:', scssFile);
    process.exit(1);
  }
  const scssContent = fs.readFileSync(scssFile, 'utf8');
  const tokens = parseCssVariables(scssContent);
  const themeJson = buildThemeJson(tokens);
  fs.writeFileSync(themeJsonFile, JSON.stringify(themeJson, null, 2));
  console.log('theme.json generated successfully.');
}

main();
