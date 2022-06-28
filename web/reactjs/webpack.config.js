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
        extensions: [".ts", ".tsx", ".js", ".jsx"],
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
};

let all = {
    name: 'all',
    mode: "development",
    entry: {
        document: `${SRC_DIR}/app/modules/plm/Document/Document.js`,
        plmDocumentReport: `${SRC_DIR}/app/modules/plm/Report/PlmDocumentReport.js`,
        plmStopReport: `${SRC_DIR}/app/modules/plm/Report/PlmStopReport.js`,
        product: `${SRC_DIR}/app/modules/references/equipment_group/EquipmentGroup.js`,
    },
    output: {
        path: `${DIST_DIR}/app`,
        filename: '[name].bundle.js',
        publicPath: "/app/"
    },
};

let document = {
    output: {
        filename: './app/document.bundle.js',
    },
    name: 'document',
    entry: `./src/app/modules/plm/Document/Document.js`,
    mode: 'development',
};
let plm_document_report = {
    output: {
        filename: './app/plmDocumentReport.bundle.js',
    },
    name: 'plm_document_report',
    entry: `./src/app/modules/plm/Report/PlmDocumentReport.js`,
    mode: 'development',
};
let plm_stop_report = {
    output: {
        filename: './app/modules/plm/report/plmStopReport.bundle.js',
    },
    name: 'plm_stop_report',
    entry: `./src/app/modules/plm/Report/PlmStopReport.js`,
    mode: 'development',
};
let equipment_group = {
    output: {
        filename: './app/EquipmentGroup.bundle.js',
    },
    name: 'equipment_group',
    entry: `./src/app/modules/references/equipment_group/EquipmentGroup.js`,
    mode: 'development',
};

/**  Base Module end*/
config = [
    {...all, ...other},
    {...document, ...other},
    {...plm_document_report, ...other},
    {...plm_stop_report, ...other},
    {...equipment_group, ...other},
];

module.exports = config;