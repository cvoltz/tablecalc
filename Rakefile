desc "Remove trailing whitespace"
task :rm_whitespace do
  `sed -i 's/[[:space:]]*$//' script.js syntax.php`
end

desc "Reformat JS code"
task :reformat_js do
  mv "script.js", "script.js.input"
  `uglifyjs --beautify --output script.js -- script.js.input`
  rm "script.js.input"
end
