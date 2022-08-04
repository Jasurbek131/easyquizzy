const path = require("path");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const DIST_DIR = path.resolve(__dirname, "dist");
const SRC_DIR = path.resolve(__dirname, "src");

let config;
let other = {
    cache: false,
    plugins: [
        new CleanWebpackPlugin({ cleanStaleWebpackAssets: false }),
        new HtmlWebpackPlugin({
            title: 'Development',
        })
    ],
    devtool: 'inline-source-map',
    watchOptions: {
        ignored: '**/node_modules',
    },
    resolve: {
        fallback: {
            dgram: false,
            crypto: false,
            fs: false,
            net: false,
            tls: false,
            child_process: false,
        }
    },
    module: {
        rules: [
            {
                test: /\.js?/,
                exclude: /(node_modules|bower_components)/,
                include: SRC_DIR,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        plugins: ['@babel/plugin-proposal-object-rest-spread']
                    }
                }
            },
            {
                test: /\.css$/i,
                use: ['css-loader'],
            },
        ]
    }
}
let all = {
    name: 'all',
    mode: "development",
    entry: {
        index: `${SRC_DIR}/app/index.js`,
    },
    output: {
        path: `${DIST_DIR}/app`,
        filename: '[name].bundle.js',
        publicPath: "/app/"
    },
};

let index = {
    output: {
        filename: './app/index.bundle.js',
    },
    name: 'index',
    entry: `./src/app/index.js`,
    mode: 'development',
};

/**  Base Module end*/
config = [
    {...index, ...other},
];
module.exports = config;