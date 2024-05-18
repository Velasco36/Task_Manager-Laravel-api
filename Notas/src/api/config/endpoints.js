function createUrl({ base = "api", endpoint, path = "", backSlash = false }) {
    const str = `${base}/${endpoint}/${path}`;

    if (str.endsWith("/") & backSlash) {
        return str.replace(/\/$/, "");
    }

    return str;
}

export const Endpoints = {
    UserProfile: {
        GetUserProfile: createUrl({ base: "user", endpoint: "userProfile" }),
    },
}
