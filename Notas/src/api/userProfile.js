import { axiosFetch, Endpoints } from "./config";

export const getuserProfile = () => {
    return axiosFetch({
        method: "GET",
        url: Endpoints.UserProfile.GetUserProfile,
    });
}
