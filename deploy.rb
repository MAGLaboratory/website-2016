PROJECT_DIRECTORY = File.dirname(__FILE__)

last_deploy = File.read('deploy.sftp').split("\n")
commit = last_deploy[0].match(/Commit: ([0-9a-f]+) ([0-9a-f]+)/)
puts "Last deploy detected as #{commit[1]} -> #{commit[2]}\n\n"

changed_files = `git diff #{commit[2]} HEAD --name-only`.split("\n")
changed_files.reject! { |i| i.start_with?('template_sources') or i.start_with?('deploy') or i.start_with?('test') }

changed_files -= %w( hamltemplater.rb config.php .htaccess .gitignore )

puts "#{changed_files.length} files changed."

Dir.chdir(PROJECT_DIRECTORY)

added_files = `git diff #{commit[2]} HEAD --name-status | grep ^A`.split("\n").collect { |i| i.split.last }


deploy_headers = ["!echo \"Commit: #{commit[2]} #{`git rev-parse HEAD`.chomp}\""]
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

exec "sftp -b deploy.sftp swut4ewr2_maglabs@nfsn:/home/protected/haldor/"
