package net.techgalore.harborguardboats;

/**
 * Created by Mahesh on 29/06/15.
 */
public class Process {

    private int boatId;
    private String name;
    private int processGroupId;
    private String materialIds;
    private int userId;
    private String startTime;
    private String endTime;
    private int createdBy;

    public int getBoatId() {
        return boatId;
    }

    public void setBoatId(int boatId) {
        this.boatId = boatId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public int getProcessGroupId() {
        return processGroupId;
    }

    public void setProcessGroupId(int processGroupId) {
        this.processGroupId = processGroupId;
    }

    public String getMaterialIds() {
        return materialIds;
    }

    public void setMaterialIds(String materialIds) {
        this.materialIds = materialIds;
    }

    public int getUserId() {
        return userId;
    }

    public void setUserId(int userId) {
        this.userId = userId;
    }

    public String getStartTime() {
        return startTime;
    }

    public void setStartTime(String startTime) {
        this.startTime = startTime;
    }

    public String getEndTime() {
        return endTime;
    }

    public void setEndTime(String endTime) {
        this.endTime = endTime;
    }

    public int getCreatedBy() {
        return createdBy;
    }

    public void setCreatedBy(int createdBy) {
        this.createdBy = createdBy;
    }

    @Override
    public String toString() {
        return "Process{" +
                "boatId=" + boatId +
                ", name='" + name + '\'' +
                ", processGroupId=" + processGroupId +
                ", materialIds='" + materialIds + '\'' +
                ", userId=" + userId +
                ", startTime='" + startTime + '\'' +
                ", endTime='" + endTime + '\'' +
                ", createdBy=" + createdBy +
                '}';
    }
}
