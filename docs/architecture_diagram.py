"""
Azure PHP CI/CD Portal - Architecture Diagram
Generated with mingrammer/diagrams
"""

from diagrams import Diagram, Cluster, Edge
from diagrams.azure.compute import AppServices
from diagrams.azure.web import AppServicePlans
from diagrams.azure.general import Resourcegroups
from diagrams.onprem.vcs import Github
from diagrams.onprem.ci import GithubActions
from diagrams.onprem.client import Users

# Graph attributes for better layout
graph_attr = {
    "fontsize": "20",
    "bgcolor": "white",
    "pad": "0.5",
    "splines": "spline",
}

with Diagram(
    "Portal Educativo PHP - Azure CI/CD",
    show=False,
    filename="docs/architecture",
    outformat="png",
    direction="LR",
    graph_attr=graph_attr,
):
    users = Users("Usuarios")
    
    with Cluster("GitHub"):
        repo = Github("Repositorio\nPHP Portal")
        actions = GithubActions("GitHub Actions\nCI/CD Pipeline")
        repo >> Edge(label="push") >> actions
    
    with Cluster("Azure Cloud"):
        with Cluster("Resource Group\nphp-cicd-portal-rg"):
            plan = AppServicePlans("App Service Plan\n(Linux F1)")
            webapp = AppServices("Linux Web App\nPHP 8.2")
            plan >> webapp
    
    # Flow
    users >> Edge(label="HTTPS") >> webapp
    actions >> Edge(label="Deploy", style="dashed", color="blue") >> webapp
