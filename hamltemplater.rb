require 'haml'
require 'pathname'

{:format => :html5, :encoding => 'UTF-8', :escape_html => true, :ugly => true, :escape_attrs => false
}.each_pair { |k, v| Haml::Options.defaults[k] = v }

Dir.glob('template_sources/*/*.haml').each do |file|
  target_path = Pathname.new("#{__dir__}/templates/#{file.gsub(/^template_sources\//, '').gsub(/\.haml$/, '')}")
  target_path.parent().mkpath
  target = target_path.open('w+')
  layout = Haml::Engine.new(File.read(file.split('/')[0..1].join('/')+'.haml'))
  
  scope = Object.new
  page = Haml::Engine.new(File.read(file)).render(scope)
  
  output = layout.render(scope) do page; end
  target.write output
  puts [output.length, file].join("\t")
end
