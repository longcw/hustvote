package com.hustvote.hustvote.net.push;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.hustvote.hustvote.utils.UserInfo;
import com.sina.push.MPSConsts;
import com.sina.push.model.ActionResult;
import com.sina.push.response.PushDataPacket;
import com.sina.push.service.message.GdidServiceMsg;

public class SAEMsgReceiver extends BroadcastReceiver {

    private String log = new String();

    @Override
    public void onReceive(Context context, Intent intent) {
        // TODO Auto-generated method stub

        Log.i("SDKMsgReceiver", "SDKMsgReceiver receive Msg.....");

        int msg_type = intent.getIntExtra(MPSConsts.CMD_ACTION, -1);
        Log.i("SDKMsgReceiver", Integer.toString(msg_type));
        switch (msg_type) {

            case MPSConsts.MSG_TYPE_GET_GDID:

                Bundle msgBundle = intent.getBundleExtra(MPSConsts.KEY_MSG_GDID);

                GdidServiceMsg msg = new GdidServiceMsg();

                msg = (GdidServiceMsg) msg.parserFromBundle(msgBundle);

                Log.i("SDKMsgReceiver", "SDKMsgReceiver gdid ===========" + msg.getGdid());

                Toast.makeText(context, msg.getGdid(), Toast.LENGTH_LONG).show();

                break;
            case MPSConsts.MSG_TYPE_MPS_PUSH_DATA:
                //收到服务器push的消息
                PushDataPacket packet = intent
                        .getParcelableExtra(MPSConsts.KEY_MSG_MPS_PUSH_DATA);

                log = "received MPS push:[appid=" + packet.getAppID() + ",msgID="
                        + packet.getMsgID() + ",srcJson=" + packet.getSrcJson()
                        + "\n";
                Log.i("SDKMsgReceiver", log);
                Toast.makeText(context, "onPush data: " + packet.getSrcJson(),
                        Toast.LENGTH_LONG).show();

                break;
            case MPSConsts.MSG_CHANNEL_HAS_BEEN_BUILDED:

                ActionResult actResult = intent
                        .getParcelableExtra(MPSConsts.KEY_MSG_ACTION_SWITCH_CHANNEL);

                log = actResult + "\n";
                Log.i("SDKMsgReceiver", log);
                if (actResult.getResultCode() == 1) {
                    // 打开通道成功，可以正常接收Push和调用接口功能
                    Log.i("SDKMsgReceiver", "channel has been builded");
                }
                break;

            case MPSConsts.MSG_TYPE_SAE_DATA:
                //获得aid
                String aid = intent.getStringExtra(MPSConsts.KEY_MSG_SAE_DATA);
                Log.i("SDKMsgReceiver", "aid:" + aid);
                UserInfo userInfo = UserInfo.getInstance(context);
                userInfo.setSAEPushToken(aid);
                if(userInfo.getUserInfoBean() == null) {
                    return;
                }
                //上传信息
                UpdateSAEPushToken.update(context.getApplicationContext(),
                        userInfo.getUserInfoBean().getUid(), aid);

                break;
            default:
                Log.i("SDKMsgReceiver", "default");
        }

    }

}
