Install daisyUI for PostCSS
How to install Tailwind CSS and daisyUI in PostCSS

1. Install
Initialize a new Node project in the current directory usingnpm init -yif it's not a Node project already.

Install PostCSS, Tailwind CSS, and daisyUI

Terminal
npm i postcss postcss-cli tailwindcss @tailwindcss/postcss daisyui@latest
2. Add Tailwind CSS and daisyUI
Create a postcss.config.mjs file and add Tailwind CSS to it

postcss.config.mjs
const config = {
  plugins: {
    '@tailwindcss/postcss': {},
  },
};
export default config;
Add Tailwind CSS and daisyUI to your CSS file.
Address your HTML and other markup files in thesourcefunction.

app.css
@import "tailwindcss" source(none);
@source "./public/*.{html,js}";
@plugin "daisyui";
3. Build CSS
Add a script to your package.json to build the CSS.

package.json
{
  "scripts": {
    "build:css": "postcss app.css -o public/output.css"
  },
}
Run the script to build the CSS file

Terminal
npm run build:css
This command creates apublic/output.cssfile with the compiled CSS. You can link this file to your HTML file.

public/index.html
<link href="./output.css" rel="stylesheet">
Now you can use daisyUI class names!