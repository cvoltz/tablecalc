desc "Remove trailing whitespace"
task :rm_whitespace do
  `sed -i 's/[[:space:]]*$//' script.js syntax.php`
end

desc "Reformat JS code"
task :reformat_js do
  `uglifyjs --beautify --output script.js -- script.js`
end
