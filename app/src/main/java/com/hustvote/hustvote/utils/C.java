package com.hustvote.hustvote.utils;

/**
 * Created by chenlong on 14-12-18.
 */
public class C {
    public class Net {
        public final static String BaseUrl = "http://10.42.0.1:8088/server/";
        public final static String SUCC_CODE = "1000";
        public class API {
            public final static String Login = "s_user/login";
            public final static String getUserInfo = "s_user/getUserInfo";
            public final static String Logout = "s_user/logout";

            public final static String getVoteList = "s_vote/getVoteList";
            public final static String getNewVote = "s_vote/getNewVote";
            public final static String getVoteDetail = "s_vote/getVoteDetail";
        }
    }
}
