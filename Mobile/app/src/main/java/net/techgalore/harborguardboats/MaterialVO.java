package net.techgalore.harborguardboats;

/**
 * Created by Mahesh on 15/07/15.
 */
public class MaterialVO {

    private int id;
    private String name;
    boolean selected = false;

    public MaterialVO() {
    }

    public MaterialVO(int id, String name, boolean selected) {
        this.id = id;
        this.name = name;
        this.selected = selected;
    }

    public int getId() {
        return this.id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return this.name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public boolean isSelected() {
        return selected;
    }

    public void setSelected(boolean selected) {
        this.selected = selected;
    }

    @Override
    public String toString() {
        return name;
    }

}
