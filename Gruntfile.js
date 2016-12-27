module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    wp_readme_to_markdown: {
      your_target: {
        files: {
          'README.md': 'src/readme.txt'
        }
      }
    },
    clean: ['build'],
    copy: {
      main: {
        files: [
          {expand: true, nonull: true, src: ['LICENSE'], dest: 'build/'},
          {expand: true, nonull: true, cwd: 'src/', src: ['readme.txt', '*.php', 'includes/*.php', 'languages/*.mo'], dest: 'build/'}
        ]
      }
    },
    wp_deploy: {
      deploy: {
        options: {
          plugin_slug: 'geocoded-posts',
          svn_user: 'svrooij',
          build_dir: 'build'
        }
      }
    }
  })

  grunt.loadNpmTasks('grunt-wp-readme-to-markdown')
  grunt.loadNpmTasks('grunt-contrib-copy')
  grunt.loadNpmTasks('grunt-wp-deploy')
  grunt.loadNpmTasks('grunt-contrib-clean')

  grunt.registerTask('build', [
    'clean',
    'wp_readme_to_markdown',
    'copy',
  ])

  grunt.registerTask('deploy', [
    'clean',
    'wp_readme_to_markdown',
    'copy',
    'wp_deploy',
  ])
}
