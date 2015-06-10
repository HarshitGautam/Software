import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.JButton;
import java.awt.TextArea;
import java.awt.Container;
import java.awt.FileDialog;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JLabel;
import java.awt.Font;
import javax.swing.JTextField;
import java.awt.SystemColor;
import java.awt.Color;
import java.io.IOException;

@SuppressWarnings("serial")
public class DISCOVERGUI extends JPanel implements ActionListener
{  
	JPanel p1 = new JPanel();
	JPanel p2 = new JPanel();
	JPanel p3 = new JPanel();
	JButton btn1 = new JButton("Message");
	JButton btn2 = new JButton("DataSource");
	private final JButton btnNewButton = new JButton("Browse...");
	private JTextField textField;
	private JTextField textField_1;
	private JTextField textField_2;
	private JFrame frmOntologyRelationshipSearching;
	private String ontologyfilePath1 = "", ontologyfilePath2 = "", PubmedfilePath = "";
	private TextArea textArea_6;
	private TextArea textArea_4;
	private TextArea textArea_3;
	private TextArea textArea_5;
	private TextArea textArea_2;
	private TextArea textArea_1;
	private TextArea textArea;
	public DISCOVERGUI()
	{
		setBackground(Color.WHITE);  
		p1.setBackground(Color.RED);
		p1.setBounds(0, 5, 1071, 33);
		p1.setLayout(null);
		btn1.setFont(new Font("Tahoma", Font.PLAIN, 15));
		btn1.setBounds(0, -5, 541, 33);
		p1.add(btn1);  
		btn2.setFont(new Font("Tahoma", Font.PLAIN, 15));
		btn2.setBounds(540, -5, 531, 33);
		p1.add(btn2);
		p2.setBounds(0, 36, 1071, 416);
		p2.setVisible(false);  
		setLayout(null);
		this.add(p1);  
		this.add(p2);  
		this.add(p3);  
		p2.setLayout(null);
		JLabel lblTarget = new JLabel("Target Ontology:");
		lblTarget.setBounds(10, 30, 97, 14);
		p2.add(lblTarget);
		btnNewButton.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				FileDialog fd1 = new FileDialog(frmOntologyRelationshipSearching);
			    fd1.setSize(400, 300);
			    fd1.setVisible(true);
			    ontologyfilePath1 = fd1.getDirectory() + fd1.getFile();
			    if(ontologyfilePath1.equals("nullnull"))
			    	ontologyfilePath1 = "";
			    textField.setText(ontologyfilePath1);
			    ontologyfilePath1 = ontologyfilePath1.replaceAll("\\\\", "/");
			}
		});
		btnNewButton.setBounds(350, 26, 89, 23);
		
		p2.add(btnNewButton);
		
		textField = new JTextField();
		textField.setBounds(112, 27, 234, 20);
		p2.add(textField);
		textField.setColumns(10);
		
		JPanel panel = new JPanel();
		panel.setBackground(SystemColor.inactiveCaption);
		panel.setBounds(10, 60, 429, 356);
		p2.add(panel);
		panel.setLayout(null);
		
		textArea = new TextArea();
		textArea.setBackground(Color.WHITE);
		textArea.setBounds(10, 31, 409, 315);
		panel.add(textArea);
		
		JLabel lblMessage = new JLabel("Message:");
		lblMessage.setBounds(10, 11, 97, 14);
		panel.add(lblMessage);
		
		JPanel panel_1 = new JPanel();
		panel_1.setBackground(SystemColor.inactiveCaption);
		panel_1.setBounds(447, 60, 624, 356);
		p2.add(panel_1);
		panel_1.setLayout(null);
		
		textArea_3 = new TextArea();
		textArea_3.setBackground(Color.WHITE);
		textArea_3.setEditable(false);
		textArea_3.setBounds(10, 31, 179, 315);
		panel_1.add(textArea_3);
		
		textArea_4 = new TextArea();
		textArea_4.setEditable(false);
		textArea_4.setBackground(Color.WHITE);
		textArea_4.setBounds(195, 31, 173, 315);
		panel_1.add(textArea_4);
		
		JLabel lblExisted = new JLabel("Existed:");
		lblExisted.setBounds(10, 11, 97, 14);
		panel_1.add(lblExisted);
		
		JLabel lblInexisted = new JLabel("In-existed:");
		lblInexisted.setBounds(195, 11, 97, 14);
		panel_1.add(lblInexisted);
		
		JLabel lblSimilarship = new JLabel("Similar-ship:");
		lblSimilarship.setBounds(374, 11, 97, 14);
		panel_1.add(lblSimilarship);
		
		textArea_5 = new TextArea();
		textArea_5.setBackground(Color.WHITE);
		textArea_5.setEditable(false);
		textArea_5.setBounds(374, 31, 240, 315);
		panel_1.add(textArea_5);
		
		JButton btnNewButton_1 = new JButton("Searching");
		btnNewButton_1.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				if(!ontologyfilePath1.isEmpty() && !textArea.getText().isEmpty())
				//if(!textArea.getText().isEmpty())
				{
					OntologyAnalyzer ontologyanalyzer = new OntologyAnalyzer(ontologyfilePath1);
					MessageAnalyzer messageanalyzer = new MessageAnalyzer(textArea.getText());
					
					try {
						ontologyanalyzer.extractingOfOntology();
					} catch (IOException e2) {
						// TODO Auto-generated catch block
						e2.printStackTrace();
					}
					
					
					try {
						messageanalyzer.extractingOfMessage();
					} catch (Exception e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					
					Comparer comparer = new Comparer(ontologyanalyzer.returnOntologySet(), messageanalyzer.returnMessageConceptSet(), ontologyanalyzer.returnDefinitionSet());
					try {
						comparer.compareSourceAndOntology();
					} catch (IOException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					textArea_4.setText(comparer.returnInexistence());
					textArea_3.setText(comparer.returnExistence());
					textArea_5.setText(comparer.returnSimilarship());
					
					ontologyanalyzer.clearOntology();
					messageanalyzer.clearMessageConcept();
				}
			}
		});
		btnNewButton_1.setFont(new Font("Tahoma", Font.BOLD, 15));
		btnNewButton_1.setBounds(915, 11, 146, 38);
		p2.add(btnNewButton_1);
		p3.setBounds(0, 36, 1071, 416);
		p3.setVisible(false);  
		p3.setLayout(null);
		JLabel lblTargetOntology = new JLabel("Target Ontology:");
		lblTargetOntology.setBounds(8, 31, 96, 14);
		p3.add(lblTargetOntology);
		
		JPanel panel_2 = new JPanel();
		panel_2.setBackground(SystemColor.inactiveCaption);
		panel_2.setBounds(446, 28, 625, 388);
		p3.add(panel_2);
		panel_2.setLayout(null);
		
		JLabel lblExisted_1 = new JLabel("Existed:");
		lblExisted_1.setBounds(10, 11, 96, 14);
		panel_2.add(lblExisted_1);
		
		JLabel lblInexisted_1 = new JLabel("In-existed:");
		lblInexisted_1.setBounds(203, 11, 96, 14);
		panel_2.add(lblInexisted_1);
		
		textArea_1 = new TextArea();
		textArea_1.setEditable(false);
		textArea_1.setBackground(Color.WHITE);
		textArea_1.setBounds(10, 31, 187, 347);
		panel_2.add(textArea_1);
		
		textArea_2 = new TextArea();
		textArea_2.setBackground(Color.WHITE);
		textArea_2.setEditable(false);
		textArea_2.setBounds(203, 31, 187, 347);
		panel_2.add(textArea_2);
		
		JLabel lblSimilarship_1 = new JLabel("Similar-ship:");
		lblSimilarship_1.setBounds(397, 11, 96, 14);
		panel_2.add(lblSimilarship_1);
		
		textArea_6 = new TextArea();
		textArea_6.setEditable(false);
		textArea_6.setBackground(Color.WHITE);
		textArea_6.setBounds(396, 31, 219, 347);
		panel_2.add(textArea_6);
		
		JLabel lblDataSource = new JLabel("Data Source:");
		lblDataSource.setBounds(8, 75, 96, 14);
		p3.add(lblDataSource);
		
		textField_1 = new JTextField();
		textField_1.setBounds(108, 28, 233, 20);
		p3.add(textField_1);
		textField_1.setColumns(10);
		
		textField_2 = new JTextField();
		textField_2.setBounds(108, 72, 233, 20);
		p3.add(textField_2);
		textField_2.setColumns(10);
		
		JButton btnNewButton_2 = new JButton("Browse...");
		btnNewButton_2.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				FileDialog fd2 = new FileDialog(frmOntologyRelationshipSearching);
			    fd2.setSize(400, 300);
			    fd2.setVisible(true);
			    ontologyfilePath2 = fd2.getDirectory() + fd2.getFile();
			    if(ontologyfilePath2.equals("nullnull"))
			    	ontologyfilePath2 = "";
			    textField_1.setText(ontologyfilePath2);
			    ontologyfilePath2 = ontologyfilePath2.replaceAll("\\\\", "/");
			}
		});
		btnNewButton_2.setBounds(350, 27, 89, 23);
		p3.add(btnNewButton_2);
		
		JButton btnNewButton_3 = new JButton("Browse...");
		btnNewButton_3.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				FileDialog fd2 = new FileDialog(frmOntologyRelationshipSearching);
		        fd2.setSize(400, 300);
		        fd2.setVisible(true);
		        PubmedfilePath = fd2.getDirectory() + fd2.getFile();
		        if(PubmedfilePath.equals("nullnull"))
		        	PubmedfilePath = "";
		        System.out.println(PubmedfilePath);
		        textField_2.setText(PubmedfilePath);
		        PubmedfilePath = PubmedfilePath.replaceAll("\\\\", "/");
			}
		});
		btnNewButton_3.setBounds(350, 71, 89, 23);
		p3.add(btnNewButton_3);
		
		JButton btnSearching = new JButton("Searching");
		btnSearching.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				if(!PubmedfilePath.isEmpty() && !ontologyfilePath2.isEmpty())
				{
					PubMedAnalyzer pubmedanalyzer = new PubMedAnalyzer(PubmedfilePath);
					OntologyAnalyzer ontologyanalyzer = new OntologyAnalyzer(ontologyfilePath2);

					try {
						ontologyanalyzer.extractingOfOntology();
					} catch (IOException e2) {
						// TODO Auto-generated catch block
						e2.printStackTrace();
					}
					try {
						pubmedanalyzer.readPubMed();
					} catch (Exception e3) {
						// TODO Auto-generated catch block
						e3.printStackTrace();
					}
				
					Comparer comparer = new Comparer(ontologyanalyzer.returnOntologySet(), pubmedanalyzer.returnPubMedConceptSet(), ontologyanalyzer.returnDefinitionSet());
					try {
						comparer.compareSourceAndOntology();
					} catch (IOException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					textArea_2.setText(comparer.returnInexistence());
					textArea_1.setText(comparer.returnExistence());
					textArea_6.setText(comparer.returnSimilarship());
				
					ontologyanalyzer.clearOntology();
					pubmedanalyzer.clearPubMed();
			}
			}
		});
		btnSearching.setFont(new Font("Tahoma", Font.BOLD, 15));
		btnSearching.setBounds(8, 119, 143, 42);
		p3.add(btnSearching);
		btn1.addActionListener(this);  
		btn2.addActionListener(this);
		
		JLabel lblNewLabel = new JLabel("Ontology Discover");
		lblNewLabel.setFont(new Font("Tahoma", Font.PLAIN, 90));
		lblNewLabel.setBounds(163, 119, 791, 230);
		add(lblNewLabel);
	} 
	
	@SuppressWarnings("deprecation")
	public static void main(String[] args) 
	{    
		JFrame frame = new JFrame("Ontology Discover");  
		frame.setSize(1077, 490);    
		Container contentPane = frame.getContentPane();   
		contentPane.add(new DISCOVERGUI());   
		frame.show(); 
		frame.setResizable(false);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	}
	
	public void actionPerformed(ActionEvent e)
	{  
		// TODO Auto-generated method stub
		String cmd = e.getActionCommand();
		if (cmd.equals("Message"))  
		{      
			p2.setVisible(true); 
			p3.setVisible(false);  
		}  
		else  
		{    
			p2.setVisible(false); 
			p3.setVisible(true); 
		} 
	}
}
