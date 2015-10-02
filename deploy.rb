PROJECT_DIRECTORY = File.dirname(__FILE__)

last_deploy = File.read('deploy.sftp').split("\n")
commit = last_deploy[0].match(/Commit: ([0-9a-f]+) ([0-9a-f]+)/)
puts "Last deploy detected as #{commit[1]} -> #{commit[2]}\n\n"

changed_files = `git diff #{commit[2]} HEAD --name-only`.split("\n")
changed_files.reject! { |i| i.start_with?('template_sources') or i.start_with?('deploy') or i.start_with?('test') }

changed_files -= %w( hamltemplater.rb config.php .htaccess .gitignore )

puts "#{changed_files.length} files changed."
puts "Writing deploy.sftp"

Dir.chdir(PROJECT_DIRECTORY)
deploy_sftp = File.open('deploy.sftp', 'w')

deploy = ["!echo \"Commit: #{commit[2]} #{`git rev-parse HEAD`.chomp}\""]
changed_files.collect do |changed_file|
  deploy << "put #{changed_file} #{changed_file}"
end

deploy_sftp.write deploy.join("\n")
deploy_sftp.close

puts "\nDeploying\n\n"

exec "sftp -b deploy.sftp swut4ewr2_maglabs@nfsn:/home/protected/haldor/"
