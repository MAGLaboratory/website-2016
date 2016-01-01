PROJECT_DIRECTORY = File.dirname(__FILE__)

# Ensure templates are up to date
`ruby hamltemplater.rb`



check_clean = `git status`

if !check_clean.include?('On branch master')
  puts "Go to master to deploy."
  exit()
end

if !check_clean.include?('nothing to commit') or !check_clean.include?('working directory clean')
  puts "Refusing to deploy. Current HEAD is dirty. Commit changes to master to deploy."
  exit();
end

last_deploy = File.read('deploy.sftp').split("\n")
commit = last_deploy[0].match(/Commit: ([0-9a-f]+) ([0-9a-f]+)/)
last_deploy_msg = "Last deploy is #{commit[1]} -> #{commit[2]}\n\n"
puts last_deploy_msg

changed_files = `git diff #{commit[2]} HEAD --name-only`.split("\n")
changed_files.reject! { |i| i.start_with?('template_sources') or i.start_with?('deploy') or i.start_with?('test') }

changed_files -= %w( hamltemplater.rb config.php .htaccess .gitignore )

changed_files_msg = "#{changed_files.length} files changed."
puts changed_files_msg


if changed_files.length == 0
  puts "No file changes since last deployment. Nothing to do."
  exit();
end

Dir.chdir(PROJECT_DIRECTORY)

added_files = `git diff #{commit[2]} HEAD --name-status | grep ^A`.split("\n").collect { |i| i.split.last }


deploy_header_msg = "Commit: #{commit[2]} #{`git rev-parse HEAD`.chomp}"
deploy_headers = ["!echo \"#{deploy_header_msg}\""]
deploy = []
create_dirs = []
skip_dirs = {}


changed_files.collect do |changed_file|
  if added_files.delete(changed_file)
    path = changed_file.split('/')
    path.pop()
    path.each_with_index { |p, i|
      new_path = (path[0...i] + [p]).join('/')
      cmd = "mkdir #{new_path}"
      # This is too inefficient, creates a new connection for each directory! ugh
      next if skip_dirs[new_path]
      check = `ssh swut4ewr2_maglabs@nfsn "ls -d '/home/protected/haldor/#{new_path}' && echo exists"`
      skip_dirs[new_path] = true
      create_dirs << cmd unless check.include?('exists')
    }
  end

  if File.exist?(changed_file)
    deploy << "put #{changed_file} #{changed_file}"
  else
    deploy << "rm #{changed_file}"
  end
end

puts "Writing deploy.sftp"
deploy_sftp = File.open('deploy.sftp', 'w')

create_dirs.uniq!
deploy_sftp.write (deploy_headers + create_dirs + deploy).join("\n")
deploy_sftp.close

puts "\nDeploying\n\n"

exec "sftp -b deploy.sftp swut4ewr2_maglabs@nfsn:/home/protected/haldor/ && git commit -am\"Deployment #{deploy_header_msg}\n\n#{last_deploy_msg}\n#{changed_files_msg}\""
