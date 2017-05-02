desc "Remove trailing whitespace"
task :rm_whitespace do
  `sed -i 's/[[:space:]]*$//' script.js syntax.php`
end

desc "Reformat JS code"
task :reformat_js do
  `uglifyjs --beautify --output script.js -- script.js`
end

desc "Reformat PHP code"
task :reformat_php do
  mv "syntax.php", "syntax.php.in"
  `php_beautifier \
    --indent_spaces \
    --filters "ArrayNested()" \
    syntax.php.in > syntax.php`
  rm "syntax.php.in"
end
