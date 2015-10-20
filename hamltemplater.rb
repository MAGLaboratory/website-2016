require 'haml'
require 'pathname'

{:format => :html5, :encoding => 'UTF-8', :escape_html => true, :ugly => true, :escape_attrs => false
}.each_pair { |k, v| Haml::Options.defaults[k] = v }

module Haml::Filters::Php
  include Haml::Filters::Base
  
  #def compile(compiler, text)
  #  super
  #end
  #
  
  def render(text)
    "<?php #{text} ?>"
  end
end

class Renderer
  def initialize(file_path)
    @file_path = file_path
    @flags = {}
  end

  def partial(name)
    partial_path = (@file_path.split('/')[0..-2] + ["_#{name}.haml"]).join('/')
    Haml::Engine.new(File.read(partial_path)).render(self)
  end
  
  def php(code)
    "<?php #{code} ?>"
  end
  
  def echo(code)
    "<?php echo #{code}; ?>"
  end
  
  # Filters specialcharacters (for use in input values, attributes, etc)
  #def yell(code)
  #  "<?php echo filter_text(#{code}, true); ?>"
  #end
  
  # Filters all htmlentities, for putting text directly on the page (inside another element)
  def say(code)
    "<?php echo filter_text(#{code}, true); ?>"
  end
  
  # declare mention recite utter yak murmur whisper hum
  def flag(x, val = nil)
    if val
      @flags[x] = val
    else
      @flags[x]
    end
  end
end


Dir.glob('template_sources/**/*.haml').each do |file|
  # skip partials and layouts
  next if file.split('/').last.start_with?('_') or file.end_with?('.layout.haml')
  
  target_path = Pathname.new("#{__dir__}/templates/#{file.gsub(/^template_sources\//, '').gsub(/\.haml$/, '')}")
  target_path.parent().mkpath
  target = target_path.open('w+')
  
  layout_paths = file.split('/')
  layout_paths.pop() # pop the filename
  layout = nil
  
  until layout or layout_paths.length == 0 do
    layout_file = layout_paths.join('/')+'.layout.haml'
    if File.exist?(layout_file)
      layout = Haml::Engine.new(File.read(layout_file))
      #puts "\tlayout: #{layout_file}"
    else
      layout_paths.pop()
    end
  end
  
  scope = Renderer.new(file)
  page = Haml::Engine.new(File.read(file)).render(scope)
  
  output = scope.flag('no_layout') ? page : (layout.render(scope) do page; end)
  target.write output
  puts [output.length, file.gsub('template_sources/', '')].join("\t")
end

Dir.glob('template_sources/**/*.php').each do |file|
  target_path = Pathname.new("#{__dir__}/templates/#{file.gsub(/^template_sources\//, '')}")
  target_path.parent().mkpath
  target = target_path.open('w')
  output = File.read(file)
  target.write output
  puts [output.length, file].join("\t")
end
