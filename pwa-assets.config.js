import {
    createAppleSplashScreens,
    defaultAssetName,
    defineConfig,
    minimal2023Preset,
} from "@vite-pwa/assets-generator/config";

export default defineConfig({
    headLinkOptions: {
        preset: "2023",
    },
    preset: {
        ...minimal2023Preset,
        appleSplashScreens: createAppleSplashScreens({
            padding: 0.6,
            resizeOptions: { background: "white", fit: "contain" },
            linkMediaOptions: {
                log: true,
                addMediaScreen: true,
                basePath: "/images/",
                xhtml: false,
            },
            png: {
                compressionLevel: 9,
                quality: 60,
            },
            name: (landscape, size, dark) => {
                return `splash/apple-splash-${
                    landscape ? "landscape" : "portrait"
                }-${
                    typeof dark === "boolean" ? (dark ? "dark-" : "light-") : ""
                }${size.width}x${size.height}.png`;
            },
        }),
        transparent: {
            sizes: [64, 192, 512],
            favicons: [[64, "../favicon.ico"]],
        },
        maskable: {
            sizes: [512],
        },
        apple: {
            sizes: [180],
        },
        assetName: (type, size) => {
            return `icons/${defaultAssetName(type, size)}`;
        },
    },
    images: ["public/images/logo.svg"],
});
