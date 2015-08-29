package net.techgalore.harborguardboats;

/**
 * Created by Mahesh on 15/07/15.
 */
public class ProductVO {

    private int id;
    private String name;

    public ProductVO() {
    }

    public ProductVO(int id, String name) {
        this.id = id;
        this.name = name;
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

}
