const Path = require( 'path' );

module.exports = {
    mode:      'development',
    entry:     {
        adminSettings: './src/js/admin/settings.js'
    },
    output:    {
        filename: 'wpbr-[name].js',
        path:     Path.resolve( __dirname, 'assets/js' )
    },
    module:    {
        rules: [
            {
                test:    /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use:     {
                    loader:  'babel-loader',
                    options: {
                        presets: [ '@babel/preset-env', '@babel/preset-react' ],
                        plugins: [ '@babel/plugin-proposal-class-properties' ]
                    }
                }
            },
            {
                test: /\.(s*)css$/,
                use:  [ 'style-loader', 'css-loader', 'sass-loader' ]
            }
        ]
    },
    externals: {
        jquery: 'jQuery'
    }
};
